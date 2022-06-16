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
require_once 'vendor/autoload.php';
use Ramsey\Uuid\Uuid;
function createTeamDrive()
{
    try {

        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google\Service\Drive::DRIVE);
        $driveService = new Google_Service_Drive($client);
        // [START createTeamDrive]
        $teamDriveMetadata = new Google_Service_Drive_TeamDrive(array(
            'name' => 'Project Resources'));
        $requestId = Uuid::uuid4()->toString();
        $teamDrive = $driveService->teamdrives->create($requestId, $teamDriveMetadata, array(
            'fields' => 'id'));
        printf("Team Drive ID: %s\n", $teamDrive->id);
        // [END createTeamDrive]
        return $teamDrive->id;

    } catch(Exception $e) {

        echo "Error Message: ".$e;
    }
   
}     
// [END createTeamDrive]
createTeamDrive();   
?>