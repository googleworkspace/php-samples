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

// [START drive_share_file]
use Google\Client;
use Google\Service\Drive;
function shareFile($fileId, $user, $domain) {
  try {
      /* Load pre-authorized user credentials from the environment.
       TODO (developer) - See https://developers.google.com/identity for
        guides on implementing OAuth2 for your application. */
       $client = new Client();
       $client->useApplicationDefaultCredentials();
       $client->addScope(Drive::DRIVE);
       $driveService = new Drive($client);
       $ids = array();
       $driveService->getClient()->setUseBatch(true);
       try {
           $batch = $driveService->createBatch();

           $userPermission = new Drive\Permission([
               'type' => 'user',
               'role' => 'writer',
               'emailAddress' => $user
           ]);

           $request = $driveService->permissions->create(
                   $fileId, $userPermission, array(['fields' => 'id']));
           $batch->add($request, 'user');
           $domainPermission = new Drive\Permission([
                   'type' => 'domain',
                   'role' => 'reader',
                   'domain' => $domain
           ]);

           $request = $driveService->permissions->create(
           $fileId, $domainPermission, array(['fields' => 'id']));
           $batch->add($request, 'domain');
           $results = $batch->execute();

           foreach ($results as $result) {
               if ($result instanceof Google\Service\Exception) {
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
  } catch(Exception $e) {
       echo "Error Message: ". $e;
  }

}
// [END drive_share_file]
require_once 'vendor/autoload.php';
shareFile("1sTWaJ_j7PkjzaBWtNc3IzovK5hQf21FbOw9yLeeLPNQ","dummy@gmail.com", "gmail.com");