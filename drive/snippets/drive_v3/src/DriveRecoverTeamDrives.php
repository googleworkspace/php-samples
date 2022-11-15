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
// [START drive_recover_team_drives]

use Google\Client;
use Google\Service\Drive;
use Ramsey\Uuid\Uuid;
function recoverTeamDrives()
{
    try {
        $client = new Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $driveService = new Drive($client);
        $teamDrives = array();
        // Find all Team Drives without an organizer and add one.
        // Note: This example does not capture all cases. Team Drives
        // that have an empty group as the sole organizer, or an
        // organizer outside the organization are not captured. A
        // more exhaustive approach would evaluate each Team Drive
        // and the associated permissions and groups to ensure an active
        // organizer is assigned.
        $pageToken = null;
        $newOrganizerPermission = new Drive\Permission(array(
            'type' => 'user',
            'role' => 'organizer',
            'emailAddress' => 'user@example.com'
        ));
        $newOrganizerPermission['emailAddress'] = 'xyz@workspace.com';

        do {
            $response = $driveService->teamdrives->listTeamdrives(array(
                'q' => 'organizerCount = 0',
                'fields' => 'nextPageToken, teamDrives(id, name)',
                'useDomainAdminAccess' => true,
                'pageToken' => $pageToken
            ));
            foreach ($response->teamDrives as $teamDrive) {
                printf("Found Team Drive without organizer: %s (%s)\n",
                    $teamDrive->name, $teamDrive->id);
                $permission = $driveService->permissions->create($teamDrive->id,
                    $newOrganizerPermission,
                    array(
                        'fields' => 'id',
                        'useDomainAdminAccess' => true,
                        'supportsTeamDrives' => true
                    ));
                printf("Added organizer permission: %s\n", $permission->id);
            }
            array_push($teamDrives, $response->teamDrives);
            $pageToken = $response->pageToken;
        } while ($pageToken != null);
        return $teamDrives;
    } catch(Exception $e) {
        echo "Error Message: ".$e;
    }
}
// [END drive_recover_team_drives]
require_once 'vendor/autoload.php';
recoverTeamDrives();