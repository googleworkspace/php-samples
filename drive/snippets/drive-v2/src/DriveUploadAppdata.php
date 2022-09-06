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
// [START drive_upload_appdata]
use Google\Client;
use Google\Service\Drive;
function uploadAppData()
{
  try {
      /* Load pre-authorized user credentials from the environment.
        TODO (developer) - See https://developers.google.com/identity for
         guides on implementing OAuth2 for your application. */
        $client = new Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $client->addScope(Drive::DRIVE_APPDATA);
        $driveService = new Drive($client);
                
        $fileMetadata = new Drive\DriveFile([
                'name' => 'config.json',
                'parents' => ['appDataFolder']
        ]);
        $content = file_get_contents('../files/config.json');
        $file = $driveService->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => 'application/json',
                'uploadType' => 'multipart',
                'fields' => 'id']);
        printf("File ID: %s\n", $file->id);
        
        return $file->id;

  } catch (Exception $e) {
        echo "Error Message: ". $e;
  }
    
}

// [END drive_upload_appdata]
require_once 'vendor/autoload.php';
uploadAppData();
