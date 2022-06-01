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
// [START drive_upload_app_data]
require_once 'vendor/autoload.php';
putenv('GOOGLE_APPLICATION_CREDENTIALS=/opt/lampp/htdocs/google-webspace/workspace-348506-241f41f76ce5.json');
function uploadAppData()
{

    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
    $client->addScope(Google\Service\Drive::DRIVE_APPDATA);
    $driveService = new Google_Service_Drive($client);
        
    $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => 'config.json',
            'parents' => array('appDataFolder')
    ));
    $content = file_get_contents('files/config.json');
    $file = $driveService->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => 'application/json',
            'uploadType' => 'multipart',
            'fields' => 'id'));
    printf("File ID: %s\n", $file->id);
     
    return $file->id;
}
// [END drive_upload_app_data]
uploadAppData();

?>