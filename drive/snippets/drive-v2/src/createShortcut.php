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

// [START drive_create_shortcut]
require_once 'vendor/autoload.php';

function createShortcut()
{
        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google\Service\Drive::DRIVE);
        $driveService = new Google\Service\Drive($client);
        
        $fileMetadata = new Google\Service\Drive\DriveFile(array(
            'name' => 'Project plan',
            'mimeType' => 'application/vnd.google-apps.drive-sdk'));
        $file = $driveService->files->create($fileMetadata, [
            'fields' => 'id'
        ]);
        printf("File ID: %s\n", $file->id);
        // [END drive_create_shortcut]
      
}
// [END drive_create_shortcut]
createShortcut();

?>
