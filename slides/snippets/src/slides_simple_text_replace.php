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

// [START slides_simple_text_replace]
function simpleTextReplace($presentationId, $shapeId, $replacementText)
{
    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
    $slidesService = new Google_Service_Slides($client);
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
    return $response;
}
// [END slides_simple_text_replace]
simpleTextReplace('12ZqIbNsOdfGr99FQJi9mQ0zDq-Q9pdf6T3ReVBz0Lms', '', '');
?>