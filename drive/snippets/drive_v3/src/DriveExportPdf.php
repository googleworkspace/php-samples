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
// [START drive_export_pdf]
use Google\Client;
use Google\Service\Drive;
function exportPdf()
{
    try {
        $client = new Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $driveService = new Drive($client);
        $realFileId = readline("Enter File Id: ");
        $fileId = '1ZdR3L3qP4Bkq8noWLJHSr_iBau0DNT4Kli4SxNc2YEo';
        $fileId = $realFileId;
        $response = $driveService->files->export($fileId, 'application/pdf', array([
            'alt' => 'media']));
        $content = $response->getBody()->getContents();
        return $content;
        
    }  catch(Exception $e) {
         echo "Error Message: ".$e;
    }
   
}
// [END drive_export_pdf]
require_once 'vendor/autoload.php';
exportPdf();