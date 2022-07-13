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

// [START slides_text_style_update]

use Google\Client;
use Google\Service\Drive;
use Google\Service\Slides;
use Google\Service\Slides\Request;
use Google\Service\Slides\BatchUpdatePresentationRequest;


function textStyleUpdate($presentationId, $shapeId)
{
    /* Load pre-authorized user credentials from the environment.
       TODO(developer) - See https://developers.google.com/identity for
        guides on implementing OAuth2 for your application. */
    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
    $slidesService = new Google_Service_Slides($client);
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
    return $response;
}

// [END slides_text_style_update]
require 'vendor/autoload.php';
textStyleUpdate('12ZqIbNsOdfGr99FQJi9mQ0zDq-Q9pdf6T3ReVBz0Lms', 'MyTextBox_01');
