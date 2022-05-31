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


// [START slides_create_slide]
function createSlide($presentationId, $pageId)
{
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
        $response = $slidesService->presentations->batchUpdate($presentationId, $batchUpdateRequest);
        $createSlideResponse = $response->getReplies()[0]->getCreateSlide();
        printf("Created slide with ID: %s\n", $createSlideResponse->getObjectId());
        return $response;
    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }
}
// [END slides_create_slide]
    createSlide('12ZqIbNsOdfGr99FQJi9mQ0zDq-Q9pdf6T3ReVBz0Lms', 'abcd1234');
?>