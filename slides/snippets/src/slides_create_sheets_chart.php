<?php
/**
 * Copyright 2022 Google Inc.
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

require __DIR__ . '/vendor/autoload.php';

// [START slides_create_sheets_chart]
function createSheetsChart($presentationId, $pageId, $spreadsheetId, $sheetChartId)
{
    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
    $slidesService = new Google_Service_Slides($client);
    // Embed a Sheets chart (indicated by the spreadsheet_id and sheet_chart_id) onto
    // a page in the presentation. Setting the linking mode as "LINKED" allows the
    // chart to be refreshed if the Sheets version is updated.
    try {

        $presentationChartId = 'MyEmbeddedChart';
        $emu4M = array('magnitude' => 4000000, 'unit' => 'EMU');
        $requests = array();
        $requests[] = new Google_Service_Slides_Request(array(
            'createSheetsChart' => array(
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
        printf("Added a linked Sheets chart with ID: %s\n", $response->getPresentationId());
        // [END slides_create_sheets_chart]
        return $response;
    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }
}
    createSheetsChart('12ZqIbNsOdfGr99FQJi9mQ0zDq-Q9pdf6T3ReVBz0Lms','abcd1234', '1sN_EOj0aYp5hn9DeqSY72G7sKaFRg82CsMGnK_Tooa8', 122);
?>