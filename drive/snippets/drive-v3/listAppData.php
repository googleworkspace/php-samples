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
// [START listAppData]
require_once 'vendor/autoload.php';
function listAppData()
{
    try {
        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google\Service\Drive::DRIVE);
        $driveService = new Google_Service_Drive($client);
        // [START listAppData]
        $response = $driveService->files->listFiles(array(
            'spaces' => 'appDataFolder',
            'fields' => 'nextPageToken, files(id, name)',
            'pageSize' => 10
        ));
        foreach ($response->files as $file) {
            printf("Found file: %s (%s)", $file->name, $file->id);
        }
        // [END listAppData]
        return $response->files;
        
    }catch(Exception $e) {
        echo "Error Message: ".$e;
    }
   
}
// [END listAppData]
listAppData();
?>
