<?php
/**
 * Copyright 2022 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
// [START drive_fetchAppDataFolder]
require_once 'vendor/autoload.php';
putenv('GOOGLE_APPLICATION_CREDENTIALS=/Users/RPSB/Documents/snippets/php_drive/drive_v3/workspace-348506-241f41f76ce5.json');
function fetchAppDataFolder()
{
    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
    $client->addScope(Google\Service\Drive::DRIVE_APPDATA);
    $driveService = new Google_Service_Drive($client);
    // [START fetchAppDataFolder]
    $file = $driveService->files->get('appDataFolder', array(
            'fields' => 'id'
    ));
    printf("Folder ID: %s\n", $file->id);
    // [END fetchAppDataFolder]
    return $file->id;
    
}
// [END drive_fetchAppDataFolder]
fetchAppDataFolder();
?>
