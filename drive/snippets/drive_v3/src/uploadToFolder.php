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
// [START drive_upload_to_folder]
use Google\Client;
use Google\Service\Drive;
function uploadToFolder()
{
    try {
        $client = new Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $driveService = new Drive($client);
         $folderId = '0BwwA4oUTeiV1TGRPeTVjaWRDY1E';
         $realFolderId = readline("Enter Folder Id: ");
         $folderId = $realFolderId;
         $fileMetadata = new Drive\DriveFile(array([
             'name' => 'photo.jpg',
             'parents' => array($folderId)
         ]));
         $content = file_get_contents('../files/photo.jpg');
         $file = $driveService->files->create($fileMetadata, array([
             'data' => $content,
             'mimeType' => 'image/jpeg',
             'uploadType' => 'multipart',
             'fields' => 'id']));
         printf("File ID: %s\n", $file->id);
         return $file->id;
    } catch(Exception $e) {
        echo "Error Message: ".$e;
    } 
  
 }
 require_once 'vendor/autoload.php';
  // [END drive_upload_basic]
 uploadToFolder();
?>