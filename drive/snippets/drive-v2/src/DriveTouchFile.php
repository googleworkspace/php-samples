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

 // [START drive_touch_file]
use Google\Client;
use Google\Service\Drive;
function touchFile($fileId, $realModifiedTime)
{
    try {
        /* Load pre-authorized user credentials from the environment.
        TODO (developer) - See https://developers.google.com/identity for
         guides on implementing OAuth2 for your application. */
        $client = new Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $driveService = new Drive($client);
        $fileMetadata = new Drive\DriveFile([
            'modifiedTime' => date('Y-m-d\TH:i:s.uP')]);
        $fileMetadata->modifiedTime = $realModifiedTime;
        $file = $driveService->files->update($fileId, $fileMetadata, [
            'fields' => 'id, modifiedTime']);
        printf("Modified time: %s\n", $file->modifiedTime);
        return $file->modifiedTime;
    } catch(Exception $e) {
        echo "Error Message: ". $e;
    }  
}
// [END drive_touch_file]
require_once 'vendor/autoload.php';
touchFile("1sTWaJ_j7PkjzaBWtNc3IzovK5hQf21FbOw9yLeeLPNQ", date('Y-m-d\TH:i:s.uP'));