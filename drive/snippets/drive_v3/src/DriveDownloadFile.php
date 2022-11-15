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
// [START drive_download_file]
use Google\Client;
use Google\Service\Drive;
function downloadFile()
 {
    try {

      $client = new Client();
      $client->useApplicationDefaultCredentials();
      $client->addScope(Drive::DRIVE);
      $driveService = new Drive($client);
      $realFileId = readline("Enter File Id: ");
      $fileId = '0BwwA4oUTeiV1UVNwOHItT0xfa2M';
      $fileId = $realFileId;
      $response = $driveService->files->get($fileId, array(
          'alt' => 'media'));
      $content = $response->getBody()->getContents();
      return $content;

    } catch(Exception $e) {
      echo "Error Message: ".$e;
    }
   
}
// [END drive_download_file]
require_once 'vendor/autoload.php';
downloadFile();