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
// [START recoverTeamDrives]
require_once 'vendor/autoload.php';
use Ramsey\Uuid\Uuid;
function recoverTeamDrives()
{
    try {
        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google\Service\Drive::DRIVE);
        $driveService = new Google_Service_Drive($client);
        $teamDrives = array();
        // [START recoverTeamDrives]
        // Find all Team Drives without an organizer and add one.
        // Note: This example does not capture all cases. Team Drives
        // that have an empty group as the sole organizer, or an
        // organizer outside the organization are not captured. A
        // more exhaustive approach would evaluate each Team Drive
        // and the associated permissions and groups to ensure an active
        // organizer is assigned.
        $pageToken = null;
        $newOrganizerPermission = new Google_Service_Drive_Permission(array(
            'type' => 'user',
            'role' => 'organizer',
            'emailAddress' => 'user@example.com'
        ));
        // [START_EXCLUDE silent]
        $newOrganizerPermission['emailAddress'] = $realUser;
        // [END_EXCLUDE]
    
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
            // [START_EXCLUDE silent]
            array_push($teamDrives, $response->teamDrives);
            // [END_EXCLUDE]
            $pageToken = $repsonse->pageToken;
        } while ($pageToken != null);
        // [END recoverTeamDrives]
        return $teamDrives;
    } catch(Exception $e) {
        echo "Error Message: ".$e;
    }
}     
// [END recoverTeamDrives]
recoverTeamDrives();   
?>
