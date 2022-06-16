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
// [START moveFileToFolder]
require_once 'vendor/autoload.php';
function moveFileToFolder()
{
    try {
        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google\Service\Drive::DRIVE);
        $driveService = new Google_Service_Drive($client);
        $realFileId = readline("Enter File Id: ");
        $realFolderId = readline("Enter Folder Id: ");
        // [START moveFileToFolder]
        $fileId = '1sTWaJ_j7PkjzaBWtNc3IzovK5hQf21FbOw9yLeeLPNQ';
        $folderId = '0BwwA4oUTeiV1TGRPeTVjaWRDY1E';
        $emptyFileMetadata = new Google_Service_Drive_DriveFile();
        // [START_EXCLUDE silent]
        $fileId = $realFileId;
        $folderId = $realFolderId;
        // [END_EXCLUDE]
        // Retrieve the existing parents to remove
        $file = $driveService->files->get($fileId, array('fields' => 'parents'));
        $previousParents = join(',', $file->parents);
        // Move the file to the new folder
        $file = $driveService->files->update($fileId, $emptyFileMetadata, array(
            'addParents' => $folderId,
            'removeParents' => $previousParents,
            'fields' => 'id, parents'));
        // [END moveFileToFolder]
        return $file->parents;
    } catch(Exception $e) {
        echo "Error Message: ".$e;
    }
     
    
}
 // [END moveFileToFolder]
 moveFileToFolder();
?>