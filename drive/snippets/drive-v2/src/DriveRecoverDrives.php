<?php
/**
 * Copyright 2022 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
// [START drive_recover_drives]
use Google\Client;
use Google\Service\Drive;
use Ramsey\Uuid\Uuid;
function recoverDrives($realUser)
{
    /* Load pre-authorized user credentials from the environment.
    TODO (developer) - See https://developers.google.com/identity for
     guides on implementing OAuth2 for your application. */
    $client = new Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Drive::DRIVE);
    $driveService = new Drive($client);
    $drives = array();
    // Find all shared drives without an organizer and add one.
    // Note: This example does not capture all cases. Shared drives
    // that have an empty group as the sole organizer, or an
    // organizer outside the organization are not captured. A
    // more exhaustive approach would evaluate each shared drive
    // and the associated permissions and groups to ensure an active
    // organizer is assigned.
    $pageToken = null;
    $newOrganizerPermission = new  Drive\Permission(array(
        'type' => 'user',
        'role' => 'organizer',
        'value' => 'user@example.com'
    ));
    $newOrganizerPermission['value'] = $realUser;
    do {
        $response = $driveService->drives->listDrives(array(
            'q' => 'organizerCount = 0',
            'useDomainAdminAccess' => true,
            'fields' => 'nextPageToken, items(id, name)',
            'pageToken' => $pageToken
        ));
        foreach ($response->items as $drive) {
            printf("Found shared drive without organizer: %s (%s)\n",
                $drive->name, $drive->id);
            $permission = $driveService->permissions->insert($drive->id,
                $newOrganizerPermission,
                array(
                    'fields' => 'id',
                    'supportsAllDrives' => true,
                    'useDomainAdminAccess' => true
                ));
            printf("Added organizer permission: %s\n", $permission->id);
        }
        array_push($drives, $response->items);
        $pageToken = $response->pageToken;
    } while ($pageToken != null);
    return $drives;
}
// [END drive_recover_drives]
require_once 'vendor/autoload.php';
recoverDrives();
