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

 // [START drive_touch_file]
require_once 'vendor/autoload.php';
putenv('GOOGLE_APPLICATION_CREDENTIALS=/opt/lampp/htdocs/google-webspace/workspace-348506-241f41f76ce5.json');
function touchFile()
{
        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google\Service\Drive::DRIVE);
        $driveService = new Google_Service_Drive($client);
        $realFileId = readline('Enter a File ID: ');
        $realModifiedTime = readline('Enter a Modified Time: ');
        $fileId = '1sTWaJ_j7PkjzaBWtNc3IzovK5hQf21FbOw9yLeeLPNQ';
        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'modifiedTime' => date('Y-m-d\TH:i:s.uP')));
        // [START_EXCLUDE silent]
        $fileId = $realFileId;
        $fileMetadata->modifiedTime = $realModifiedTime;
        // [END_EXCLUDE]
        $file = $driveService->files->update($fileId, $fileMetadata, array(
            'fields' => 'id, modifiedTime'));
        printf("Modified time: %s\n", $file->modifiedTime);
        
        return $file->modifiedTime;
}
// [END drive_touch_file]
touchFile();
        
?>
