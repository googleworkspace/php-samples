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

// [START slides_create_slide]
use Google\Client;
use Google\Service\Drive;
use Google\Service\Slides\Request;
use Google\Service\Slides\BatchUpdatePresentationRequest;

function createSlide($presentationId, $pageId)
{
    /* Load pre-authorized user credentials from the environment.
   TODO(developer) - See https://developers.google.com/identity for
    guides on implementing OAuth2 for your application. */
    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
    $slidesService = new Google_Service_Slides($client);
    try {
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
        $batchUpdateRequest = new Google_Service_Slides_BatchUpdatePresentationRequest(array(
            'requests' => $requests
        ));

        //execute the request 
        $response = $slidesService->presentations->batchUpdate($presentationId, $batchUpdateRequest);
        $createSlideResponse = $response->getReplies()[0]->getCreateSlide();
        printf("Created slide with ID: %s\n", $createSlideResponse->getObjectId());
        return $response;
    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }
}

// [END slides_create_slide]
require 'vendor/autoload.php';
createSlide('12ZqIbNsOdfGr99FQJi9mQ0zDq-Q9pdf6T3ReVBz0Lms', 'ss1234');
