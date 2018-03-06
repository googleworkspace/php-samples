<?php
/**
 * Copyright 2018 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
class SlidesSnippets
{
    public function __construct($service, $driveService, $sheetsService)
    {
        $this->service = $service;
        $this->driveService = $driveService;
        $this->sheetsService = $sheetsService;
    }

    public function createPresentation($title)
    {
        $slidesService = $this->service;
        // [START createPresentation]
        $presentation = new Google_Service_Slides_Presentation(array(
            'title' => $title
        ));

        $presentation = $slidesService->presentations->create($presentation);
        printf("Created presentation with ID: %s\n", $presentation->presentationId);
        // [END createPresentation]
        return $presentation;
    }

    public function copyPresentation($presentationId, $copyTitle)
    {
        $driveService = $this->driveService;
        // [START copyPresentation]
        $copy = new Google_Service_Drive_DriveFile(array(
            'name' => $copyTitle
        ));
        $driveResponse = $driveService->files->copy($presentationId, $copy);
        $presentationCopyId = $driveResponse->id;
        // [END copyPresentation]
        return $presentationCopyId;
    }

    public function createSlide($presentationId, $pageId)
    {
        $slidesService = $this->service;
        // [START createSlide]
        // Add a slide at index 1 using the predefined 'TITLE_AND_TWO_COLUMNS' layout and
        // the ID page_id.
        $requests = array();
        $requests[] = new Google_Service_Slides_Request(array(
            'createSlide' => array(
                'objectId' => $pageId,
                'insertionIndex' => 1,
                'slideLayoutReference' => array(
                    'predefinedLayout' => 'TITLE_AND_TWO_COLUMNS'
                )
            )
        ));

        // If you wish to populate the slide with elements, add element create requests here,
        // using the page_id.

        // Execute the request.
        $batchUpdateRequest = new Google_Service_Slides_BatchUpdatePresentationRequest(array(
            'requests' => $requests
        ));
        $response = $slidesService->presentations->batchUpdate($presentationId, $batchUpdateRequest);
        $createSlideResponse = $response->getReplies()[0]->getCreateSlide();
        printf("Created slide with ID: %s\n", $createSlideResponse->getObjectId());
        // [END createSlide]
        return $response;
    }

    public function createTextboxWithText($presentationId, $pageId)
    {
        $slidesService = $this->service;
        // [START createTextboxWithText]
        // Create a new square textbox, using the supplied element ID.
        $elementId = 'MyTextBox_01';
        $pt350 = array('magnitude' => 350, 'unit' => 'PT');
        $requests = array();
        $requests[] = new Google_Service_Slides_Request(array(
            'createShape' => array(
                'objectId' => $elementId,
                'shapeType' => 'TEXT_BOX',
                'elementProperties' => array(
                    'pageObjectId' => $pageId,
                    'size' => array(
                        'height' => $pt350,
                        'width' => $pt350
                    ),
                    'transform' => array(
                        'scaleX' => 1,
                        'scaleY' => 1,
                        'translateX' => 350,
                        'translateY' => 100,
                        'unit' => 'PT'
                    )
                )
            )
        ));

        // Insert text into the box, using the supplied element ID.
        $requests[] = new Google_Service_Slides_Request(array(
            'insertText' => array(
                'objectId' => $elementId,
                'insertionIndex' => 0,
                'text' => 'New Box Text Inserted!'
            )
        ));

        // Execute the requests.
        $batchUpdateRequest = new Google_Service_Slides_BatchUpdatePresentationRequest(array(
            'requests' => $requests
        ));
        $response = $slidesService->presentations->batchUpdate($presentationId, $batchUpdateRequest);
        $createShapeResponse = $response->getReplies()[0]->getCreateShape();
        printf("Created textbox with ID: %s\n", $createShapeResponse->getObjectId());
        // [END createTextboxWithText]
        return $response;
    }

    public function createImage($presentationId, $pageId, $imageFilePath, $imageMimeType)
    {
        $slidesService = $this->service;
        $driveService = $this->driveService;
        // [START createImage]
        // Temporarily upload a local image file to Drive, in order to obtain a URL
        // for the image. Alternatively, you can provide the Slides servcie a URL of
        // an already hosted image.
        $file = new Google_Service_Drive_DriveFile(array(
            'name' => 'My Image File',
            'mimeType' => $imageMimeType
        ));
        $params = array(
            'data' => file_get_contents($imageFilePath),
            'uploadType' => 'media',
        );
        $upload = $driveService->files->create($file, $params);
        $fileId = $upload->id;

        // Obtain a URL for the image.
        $token = $driveService->getClient()->fetchAccessTokenWithAssertion()['access_token'];
        $endPoint = 'https://www.googleapis.com/drive/v3/files';
        $imageUrl = sprintf('%s/%s?alt=media&access_token=%s', $endPoint, $fileId, $token);

        // Create a new image, using the supplied object ID, with content downloaded from image_url.
        $imageId = 'MyImage_01';
        $emu4M = array('magnitude' => 4000000, 'unit' => 'EMU');
        $requests = array();
        $requests[] = new Google_Service_Slides_Request(array(
            'createImage' => array(
                'objectId' => $imageId,
                'url' => $imageUrl,
                'elementProperties' => array(
                    'pageObjectId' => $pageId,
                    'size' => array(
                        'height' => $emu4M,
                        'width' => $emu4M
                    ),
                    'transform' => array(
                        'scaleX' => 1,
                        'scaleY' => 1,
                        'translateX' => 100000,
                        'translateY' => 100000,
                        'unit' => 'EMU'
                    )
                )
            )
        ));

        // Execute the request.
        $batchUpdateRequest = new Google_Service_Slides_BatchUpdatePresentationRequest(array(
            'requests' => $requests
        ));
        $response = $slidesService->presentations->batchUpdate($presentationId, $batchUpdateRequest);
        $createImageResponse = $response->getReplies()[0]->getCreateImage();
        printf("Created image with ID: %s\n", $createImageResponse->getObjectId());

        // Remove the temporary image file from Drive.
        $driveService->files->delete($fileId);
        // [END createImage]
        return $response;
    }

    public function textMerging($templatePresentationId, $dataSpreadsheetId)
    {
        $slidesService = $this->service;
        $driveService = $this->driveService;
        $sheetsService = $this->sheetsService;

        $responses = array();
        // [START textMerging]
        // Use the Sheets API to load data, one record per row.
        $dataRangeNotation = 'Customers!A2:M6';
        $sheetsResponse =
            $sheetsService->spreadsheets_values->get($dataSpreadsheetId, $dataRangeNotation);
        $values = $sheetsResponse['values'];

        // For each record, create a new merged presentation.
        foreach ($values as $row) {
            $customerName = $row[2];     // name in column 3
            $caseDescription = $row[5];  // case description in column 6
            $totalPortfolio = $row[11];  // total portfolio in column 12

            // Duplicate the template presentation using the Drive API.
            $copy = new Google_Service_Drive_DriveFile(array(
                'name' => $customerName . ' presentation'
            ));
            $driveResponse = $driveService->files->copy($templatePresentationId, $copy);
            $presentationCopyId = $driveResponse->id;

            // Create the text merge (replaceAllText) requests for this presentation.
            $requests = array();
            $requests[] = new Google_Service_Slides_Request(array(
                'replaceAllText' => array(
                    'containsText' => array(
                        'text' => '{{customer-name}}',
                        'matchCase' => true
                    ),
                    'replaceText' => $customerName
                )
            ));
            $requests[] = new Google_Service_Slides_Request(array(
                'replaceAllText' => array(
                    'containsText' => array(
                        'text' => '{{case-description}}',
                        'matchCase' => true
                    ),
                    'replaceText' => $caseDescription
                )
            ));
            $requests[] = new Google_Service_Slides_Request(array(
                'replaceAllText' => array(
                    'containsText' => array(
                        'text' => '{{total-portfolio}}',
                        'matchCase' => true
                    ),
                    'replaceText' => $totalPortfolio
                )
            ));

            // Execute the requests for this presentation.
            $batchUpdateRequest = new Google_Service_Slides_BatchUpdatePresentationRequest(array(
                'requests' => $requests
            ));
            $response =
                $slidesService->presentations->batchUpdate($presentationCopyId, $batchUpdateRequest);
            // [START_EXCLUDE silent]
            $responses[] = $response;
            // [END_EXCLUDE]
            // Count the total number of replacements made.
            $numReplacements = 0;
            foreach ($response->getReplies() as $reply) {
                $numReplacements += $reply->getReplaceAllText()->getOccurrencesChanged();
            }
            printf("Created presentation for %s with ID: %s\n", $customerName, $presentationCopyId);
            printf("Replaced %d text instances.\n", $numReplacements);
        }
        // [END textMerging]
        return $responses;
    }

    public function imageMerging($templatePresentationId, $imageUrl, $customerName)
    {
        $slidesService = $this->service;
        $driveService = $this->driveService;
        $logoUrl = $imageUrl;
        $customerGraphicUrl = $imageUrl;
        // [START imageMerging]
        // Duplicate the template presentation using the Drive API.
        $copy = new Google_Service_Drive_DriveFile(array(
            'name' => $customerName . ' presentation'
        ));
        $driveResponse = $driveService->files->copy($templatePresentationId, $copy);
        $presentationCopyId = $driveResponse->id;

        // Create the image merge (replaceAllShapesWithImage) requests.
        $requests = array();
        $requests[] = new Google_Service_Slides_Request(array(
            'replaceAllShapesWithImage' => array(
                'imageUrl' => $logoUrl,
                'replaceMethod' => 'CENTER_INSIDE',
                'containsText' => array(
                    'text' => '{{company-logo}}',
                    'matchCase' => true
                )
            )
        ));
        $requests[] = new Google_Service_Slides_Request(array(
            'replaceAllShapesWithImage' => array(
                'imageUrl' => $customerGraphicUrl,
                'replaceMethod' => 'CENTER_INSIDE',
                'containsText' => array(
                    'text' => '{{customer-graphic}}',
                    'matchCase' => true
                )
            )
        ));

        // Execute the requests.
        $batchUpdateRequest = new Google_Service_Slides_BatchUpdatePresentationRequest(array(
            'requests' => $requests
        ));
        $response =
            $slidesService->presentations->batchUpdate($presentationCopyId, $batchUpdateRequest);

        // Count the total number of replacements made.
        $numReplacements = 0;
        foreach ($response->getReplies() as $reply) {
            $numReplacements += $reply->getReplaceAllShapesWithImage()->getOccurrencesChanged();
        }
        printf("Created presentation for %s with ID: %s\n", $customerName, $presentationCopyId);
        printf("Replaced %d shapes with images.\n", $numReplacements);
        // [END imageMerging]
        return $response;
    }

    public function simpleTextReplace($presentationId, $shapeId, $replacementText)
    {
        $slidesService = $this->service;
        // [START simpleTextReplace]
        // Remove existing text in the shape, then insert new text.
        $requests = array();
        $requests[] = new Google_Service_Slides_Request(array(
            'deleteText' => array(
                'objectId' => $shapeId,
                'textRange' => array(
                    'type' => 'ALL'
                )
            )
        ));
        $requests[] = new Google_Service_Slides_Request(array(
            'insertText' => array(
                'objectId' => $shapeId,
                'insertionIndex' => 0,
                'text' => $replacementText
            )
        ));

        // Execute the requests.
        $batchUpdateRequest = new Google_Service_Slides_BatchUpdatePresentationRequest(array(
            'requests' => $requests
        ));
        $response = $slidesService->presentations->batchUpdate($presentationId, $batchUpdateRequest);
        printf("Replaced text in shape with ID: %s", $shapeId);
        // [END simpleTextReplace]
        return $response;
    }

    public function textStyleUpdate($presentationId, $shapeId)
    {
        $slidesService = $this->service;
        // [START textStyleUpdate]
        // Update the text style so that the first 5 characters are bolded
        // and italicized, the next 5 are displayed in blue 14 pt Times
        // New Roman font, and the next 5 are hyperlinked.
        $requests = array();
        $requests[] = new Google_Service_Slides_Request(array(
            'updateTextStyle' => array(
                'objectId' => $shapeId,
                'textRange' => array(
                    'type' => 'FIXED_RANGE',
                    'startIndex' => 0,
                    'endIndex' => 5
                ),
                'style' => array(
                    'bold' => true,
                    'italic' => true
                ),
                'fields' => 'bold,italic'
            )
        ));
        $requests[] = new Google_Service_Slides_Request(array(
            'updateTextStyle' => array(
                'objectId' => $shapeId,
                'textRange' => array(
                    'type' => 'FIXED_RANGE',
                    'startIndex' => 5,
                    'endIndex' => 10
                ),
                'style' => array(
                    'fontFamily' => 'Times New Roman',
                    'fontSize' => array(
                        'magnitude' => 14,
                        'unit' => 'PT'
                    ),
                    'foregroundColor' => array(
                        'opaqueColor' => array(
                            'rgbColor' => array(
                                'blue' => 1.0,
                                'green' => 0.0,
                                'red' => 0.0
                            )
                        )
                    )
                ),
                'fields' => 'foregroundColor,fontFamily,fontSize'
            )
        ));
        $requests[] = new Google_Service_Slides_Request(array(
            'updateTextStyle' => array(
                'objectId' => $shapeId,
                'textRange' => array(
                    'type' => 'FIXED_RANGE',
                    'startIndex' => 10,
                    'endIndex' => 15
                ),
                'style' => array(
                    'link' => array(
                        'url' => 'www.example.com'
                    )
                ),
                'fields' => 'link'
            )
        ));

        // Execute the requests.
        $batchUpdateRequest = new Google_Service_Slides_BatchUpdatePresentationRequest(array(
            'requests' => $requests
        ));
        $response = $slidesService->presentations->batchUpdate($presentationId, $batchUpdateRequest);
        printf("Updated the text style for shape with ID: %s", $shapeId);
        // [END textStyleUpdate]
        return $response;
    }

    public function createBulletedText($presentationId, $shapeId)
    {
        $slidesService = $this->service;
        // [START createBulletedText]
        // Add arrow-diamond-disc bullets to all text in the shape.
        $requests = array();
        $requests[] = new Google_Service_Slides_Request(array(
            'createParagraphBullets' => array(
                'objectId' => $shapeId,
                'textRange' => array(
                    'type' => 'ALL'
                ),
                'bulletPreset' => 'BULLET_ARROW_DIAMOND_DISC'
            )
        ));

        // Execute the request.
        $batchUpdateRequest = new Google_Service_Slides_BatchUpdatePresentationRequest(array(
            'requests' => $requests
        ));
        $response = $slidesService->presentations->batchUpdate($presentationId, $batchUpdateRequest);
        printf("Added bullets to text in shape with ID: %s", $shapeId);
        // [END createBulletedText]
        return $response;
    }

    public function createSheetsChart($presentationId, $pageId, $spreadsheetId, $sheetChartId)
    {
        $slidesService = $this->service;
        // [START createSheetsChart]
        // Embed a Sheets chart (indicated by the spreadsheet_id and sheet_chart_id) onto
        // a page in the presentation. Setting the linking mode as "LINKED" allows the
        // chart to be refreshed if the Sheets version is updated.
        $presentationChartId = 'MyEmbeddedChart';
        $emu4M = array('magnitude' => 4000000, 'unit' => 'EMU');
        $requests = array();
        $requests[] = new Google_Service_Slides_Request(array(
            'createSheetsChart' => array(
                'objectId' => $shapeId,
                'spreadsheetId' => $spreadsheetId,
                'chartId' => $sheetChartId,
                'linkingMode' => 'LINKED',
                'elementProperties' => array(
                    'pageObjectId' => $pageId,
                    'size' => array(
                        'height' => $emu4M,
                        'width' => $emu4M
                    ),
                    'transform' => array(
                        'scaleX' => 1,
                        'scaleY' => 1,
                        'translateX' => 100000,
                        'translateY' => 100000,
                        'unit' => 'EMU'
                    )
                )
            )
        ));

        // Execute the request.
        $batchUpdateRequest = new Google_Service_Slides_BatchUpdatePresentationRequest(array(
            'requests' => $requests
        ));
        $response = $slidesService->presentations->batchUpdate($presentationId, $batchUpdateRequest);
        printf("Added a linked Sheets chart with ID: %s", $shapeId);
        // [END createSheetsChart]
        return $response;
    }

    public function refreshSheetsChart($presentationId, $presentationChartId)
    {
        $slidesService = $this->service;
        // [START refreshSheetsChart]
        // Refresh an existing linked Sheets chart embedded in a presentation.
        $requests = array();
        $requests[] = new Google_Service_Slides_Request(array(
            'refreshSheetsChart' => array(
                'objectId' => $presentationChartId
            )
        ));

        // Execute the request.
        $batchUpdateRequest = new Google_Service_Slides_BatchUpdatePresentationRequest(array(
            'requests' => $requests
        ));
        $response = $slidesService->presentations->batchUpdate($presentationId, $batchUpdateRequest);
        printf("Refreshed a linked Sheets chart with ID: %s", $shapeId);
        // [END refreshSheetsChart]
        return $response;
    }
}
