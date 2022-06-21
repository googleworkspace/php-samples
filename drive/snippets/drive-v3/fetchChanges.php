<?php
/**
* Copyright 2022 Google Inc.
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/
# [START fetchChanges]
use Google\Client;
use Google\Service\Drive;
# TODO - PHP client currently chokes on fetching start page token
function fetchChanges()
{
    try {
        $client = new Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $driveService = new Drive($client);
       # Begin with our last saved start token for this user or the
        # current token from getStartPageToken()
        $savedStartPageToken = readLine("Enter Start Page Token: ");
        $pageToken = $savedStartPageToken;
        while ($pageToken != null) {
            $response = $driveService->changes->listChanges($pageToken, array([
                'spaces' => 'drive'
            ]));
            foreach ($response->changes as $change) {
                // Process change
                printf("Change found for file: %s", $change->fileId);
            }
            if ($response->newStartPageToken != null) {
                // Last page, save this token for the next polling interval
                $savedStartPageToken = $response->newStartPageToken;
            }
            $pageToken = $response->nextPageToken;
        }
        // [END fetchChanges]
        echo $savedStartPageToken;
    } catch(Exception $e) {
        echo "Error Message: ".$e;
    }
    
}
require_once 'vendor/autoload.php';
 # [END fetchChanges]
fetchChanges();

?>
