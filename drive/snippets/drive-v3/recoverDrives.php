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
// [START recoverDrives]
use Google\Client;
use Google\Service\Drive;
use Ramsey\Uuid\Uuid;
function recoverDrives()
{
   try {
    $client = new Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Drive::DRIVE);
    $driveService = new Drive($client);

    $realUser = readline("Enter user email address: ");

    $drives = array();
    // [START recoverDrives]
    // Find all shared drives without an organizer and add one.
    // Note: This example does not capture all cases. Shared drives
    // that have an empty group as the sole organizer, or an
    // organizer outside the organization are not captured. A
    // more exhaustive approach would evaluate each shared drive
    // and the associated permissions and groups to ensure an active
    // organizer is assigned.
    $pageToken = null;
    $newOrganizerPermission = new Drive\Permission(array([
        'type' => 'user',
        'role' => 'organizer',
        'emailAddress' => 'user@example.com'
    ]));
    // [START_EXCLUDE silent]
    $newOrganizerPermission['emailAddress'] = $realUser;
    // [END_EXCLUDE]

    do {
        $response = $driveService->drives->listDrives(array([
            'q' => 'organizerCount = 0',
            'fields' => 'nextPageToken, drives(id, name)',
            'useDomainAdminAccess' => true,
            'pageToken' => $pageToken
        ]));
        foreach ($response->drives as $drive) {
            printf("Found shared drive without organizer: %s (%s)\n",
                $drive->name, $drive->id);
            $permission = $driveService->permissions->create($drive->id,
                $newOrganizerPermission,
                array(
                    'fields' => 'id',
                    'useDomainAdminAccess' => true,
                    'supportsAllDrives' => true
                ));
            printf("Added organizer permission: %s\n", $permission->id);
        }
        // [START_EXCLUDE silent]
        array_push($drives, $response->drives);
        // [END_EXCLUDE]
        $pageToken = $repsonse->pageToken;
    } while ($pageToken != null);
    // [END recoverDrives]
    return $drives;
   } catch(Exception $e) {
      echo "Error Message: ".$e;
   }
}
require_once 'vendor/autoload.php';     
// [END recoverDrives]
recoverDrives();   
?>
