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
// [START drive_share_file]
use Google\Client;
use Google\Service\Drive;
function shareFile()
{
    try {
        $client = new Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $driveService = new Drive($client);
        $realFileId = readline("Enter File Id: ");
        $realUser = readline("Enter user email address: ");
        $realDomain = readline("Enter domain name: ");
        $ids = array();
            $fileId = '1sTWaJ_j7PkjzaBWtNc3IzovK5hQf21FbOw9yLeeLPNQ';
            $fileId = $realFileId;
            $driveService->getClient()->setUseBatch(true);
            try {
                $batch = $driveService->createBatch();
    
                $userPermission = new Drive\Permission(array([
                    'type' => 'user',
                    'role' => 'writer',
                    'emailAddress' => 'user@example.com'
                ]));
                $userPermission['emailAddress'] = $realUser;
                $request = $driveService->permissions->create(
                    $fileId, $userPermission, array(['fields' => 'id']));
                $batch->add($request, 'user');
                $domainPermission = new Drive\Permission(array([
                    'type' => 'domain',
                    'role' => 'reader',
                    'domain' => 'example.com'
                ]));
                $userPermission['domain'] = $realDomain;
                $request = $driveService->permissions->create(
                    $fileId, $domainPermission, array(['fields' => 'id']));
                $batch->add($request, 'domain');
                $results = $batch->execute();
    
                foreach ($results as $result) {
                    if ($result instanceof Google_Service_Exception) {
                        // Handle error
                        printf($result);
                    } else {
                        printf("Permission ID: %s\n", $result->id);
                        array_push($ids, $result->id);
                    }
                }
            } finally {
                $driveService->getClient()->setUseBatch(false);
            }
            return $ids;
    } catch(Exception $e) {
        echo "Error Message: ".$e;
    }
   
}
// [END drive_share_file]
require_once 'vendor/autoload.php';
shareFile();