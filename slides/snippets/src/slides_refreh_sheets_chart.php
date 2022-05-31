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

    // [START slides_refresh_sheets_chart]
function refreshSheetsChart($presentationId, $presentationChartId)
{
    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
    $slidesService = new Google_Service_Slides($client);
    try {

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
        printf("Refreshed a linked Sheets chart with ID: %s\n", $response->getPresentationId());
        return $response;
    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }
}
// [END slides_refresh_sheets_chart]
refreshSheetsChart('12ZqIbNsOdfGr99FQJi9mQ0zDq-Q9pdf6T3ReVBz0Lms', 'chartId');
?>