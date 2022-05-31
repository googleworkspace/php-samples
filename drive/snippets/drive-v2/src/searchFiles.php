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
 // [START drive_search_files]
require_once 'vendor/autoload.php';
putenv('GOOGLE_APPLICATION_CREDENTIALS=/opt/lampp/htdocs/google-webspace/workspace-348506-241f41f76ce5.json');
function searchFiles() {
        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google\Service\Drive::DRIVE);
        $driveService = new Google_Service_Drive($client);
        $files = array();
       
        $pageToken = null;
        do {
            $response = $driveService->files->listFiles(array(
                'q' => "mimeType='image/jpeg'",
                'spaces' => 'drive',
                'pageToken' => $pageToken,
                'fields' => 'nextPageToken, files(id, name)',
            ));
            foreach ($response->files as $file) {
                printf("Found file: %s (%s)\n", $file->name, $file->id);
            }
            // [START_EXCLUDE silent]
            array_push($files, $response->files);
            // [END_EXCLUDE]

            $pageToken = $response->pageToken;
        } while ($pageToken != null);
       
       // print_r($files);
         
}
 // [END drive_search_files]
searchFiles();
?>