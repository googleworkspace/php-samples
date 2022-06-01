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
 // [START drive_share_file]
require_once 'vendor/autoload.php';
putenv('GOOGLE_APPLICATION_CREDENTIALS=/opt/lampp/htdocs/google-webspace/workspace-348506-241f41f76ce5.json');
function shareFile() {

    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
    $driveService = new Google_Service_Drive($client);
    $realFileId = readline('Enter a File ID: ');
    $realUser = readline('Enter a User Email Address: ');
    $realDomain = readline('Enter a Domain: ');
    $ids = array();
       
    $fileId = '1sTWaJ_j7PkjzaBWtNc3IzovK5hQf21FbOw9yLeeLPNQ';
        // [START_EXCLUDE silent]
    $fileId = $realFileId;
    // [END_EXCLUDE]
    $driveService->getClient()->setUseBatch(true);
    try {
        $batch = $driveService->createBatch();

        $userPermission = new Google_Service_Drive_Permission(array(
            'type' => 'user',
            'role' => 'writer',
            'emailAddress' => 'user@example.com'
        ));
        // [START_EXCLUDE silent]
        $userPermission['emailAddress'] = $realUser;
        // [END_EXCLUDE]
        $request = $driveService->permissions->create(
                $fileId, $userPermission, array('fields' => 'id'));
        $batch->add($request, 'user');
        $domainPermission = new Google_Service_Drive_Permission(array(
                'type' => 'domain',
                'role' => 'reader',
                'domain' => 'example.com'
        ));
        // [START_EXCLUDE silent]
        $userPermission['domain'] = $realDomain;
        // [END_EXCLUDE]
        $request = $driveService->permissions->create(
        $fileId, $domainPermission, array('fields' => 'id'));
        $batch->add($request, 'domain');
        $results = $batch->execute();

        foreach ($results as $result) {
            if ($result instanceof Google_Service_Exception) {
                // Handle error
                printf($result);
            } else {
                printf("Permission ID: %s\n", $result->id);
                // [START_EXCLUDE silent]
                array_push($ids, $result->id);
                // [END_EXCLUDE]
            }
        }
        } finally {
            $driveService->getClient()->setUseBatch(false);
        }
       
        print_r($ids);
            
}
 // [END drive_share_file]
shareFile();
?>

