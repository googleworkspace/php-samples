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
// [START drive_move_file_to_folder]
use Google\Client;
use Google\Service\Drive;
function moveFileToFolder($fileId, $folderId)
{
    try {
        /* Load pre-authorized user credentials from the environment.
        TODO (developer) - See https://developers.google.com/identity for
         guides on implementing OAuth2 for your application. */
        $client = new Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $driveService = new Drive($client);
        $emptyFileMetadata = new Drive\DriveFile();
        // Retrieve the existing parents to remove
        $file = $driveService->files->get($fileId, array(['fields' => 'parents']));
        $previousParents = join(',', $file->parents);
        // Move the file to the new folder
        $file = $driveService->files->update($fileId, $emptyFileMetadata, array([
            'addParents' => $folderId,
            'removeParents' => $previousParents,
            'fields' => 'id, parents']));
        
        print_r($file->parents);
    } catch(Exception $e) {
        echo "Error Message: ". $e;
    }      
}
// [END drive_move_file_to_folder]
require_once 'vendor/autoload.php';
moveFileToFolder("1sTWaJ_j7PkjzaBWtNc3IzovK5hQf21FbOw9yLeeLPNQ","0BwwA4oUTeiV1TGRPeTVjaWRDY1E");