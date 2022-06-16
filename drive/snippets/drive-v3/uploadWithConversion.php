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
// [START uploadWithConversion]
require_once 'vendor/autoload.php';
function uploadWithConversion()
{
    try {
        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google\Service\Drive::DRIVE);
        $driveService = new Google_Service_Drive($client);
        // [START uploadWithConversion]
        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => 'My Report',
            'mimeType' => 'application/vnd.google-apps.spreadsheet'));
        $content = file_get_contents('../files/report.csv');
        $file = $driveService->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => 'text/csv',
            'uploadType' => 'multipart',
            'fields' => 'id'));
        printf("File ID: %s\n", $file->id);
        // [END uploadWithConversion]
        return $file->id;
    } catch(Exception $e) {
        echo "Error Message: ".$e;
    }
    
 }
// [END uploadWithConversion]
uploadWithConversion();
?>