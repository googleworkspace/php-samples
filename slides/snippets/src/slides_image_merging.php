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

// [START slides_image_merging]
function imageMerging($templatePresentationId, $imageUrl, $customerName)
{
    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
    $slidesService = new Google_Service_Slides($client);
    $driveService = new Google_Service_Drive($client);
    $logoUrl = $imageUrl;
    $customerGraphicUrl = $imageUrl;
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
    return $response;
}
// [END slides_image_merging]

    imageMerging('12ZqIbNsOdfGr99FQJi9mQ0zDq-Q9pdf6T3ReVBz0Lms', 'https://www.google.com/images/branding/'
    . 'googlelogo/2x/googlelogo_color_272x92dp.png', "kirmada");
?>