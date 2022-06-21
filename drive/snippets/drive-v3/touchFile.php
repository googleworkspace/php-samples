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
// [START touchFile]
use Google\Client;
use Google\Service\Drive;
function touchFile()
{
    try {
        $client = new Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $driveService = new Drive($client);
        $realFileId = readline("Enter File Id: ");
        $realModifiedTime = readline("Enter Modified Time: ");
        // [START touchFile]
        $fileId = '1sTWaJ_j7PkjzaBWtNc3IzovK5hQf21FbOw9yLeeLPNQ';
        $fileMetadata = new Drive\DriveFile(array([
            'modifiedTime' => date('Y-m-d\TH:i:s.uP')]));
        // [START_EXCLUDE silent]
        $fileId = $realFileId;
        $fileMetadata->modifiedTime = $realModifiedTime;
        // [END_EXCLUDE]
        $file = $driveService->files->update($fileId, $fileMetadata, array([
            'fields' => 'id, modifiedTime']));
        printf("Modified time: %s\n", $file->modifiedTime);
        // [END touchFile]
        return $file->modifiedTime;
    } catch(Exception $e) {
        echo "Error Message: ".$e;
    }
   
}
require_once 'vendor/autoload.php';
// [END touchFile]
touchFile();
?>