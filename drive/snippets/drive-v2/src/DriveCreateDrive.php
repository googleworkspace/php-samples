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
// [START drive_create_drive]
use Google\Client;
use Google\Service\Drive;
use Ramsey\Uuid\Uuid;
function createDrive() {
    /* Load pre-authorized user credentials from the environment.
    TODO (developer) - See https://developers.google.com/identity for
     guides on implementing OAuth2 for your application. */
    $client = new Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Drive::DRIVE);
    $driveService = new Drive($client);
    $driveMetadata = new Drive\DriveFile(array(
        'name' => 'Project Resources'));
    $requestId = Uuid::uuid4()->toString();
    $drive = $driveService->drives->insert($requestId, $driveMetadata, array(
        'fields' => 'id'));
    printf("Drive ID: %s\n", $drive->id);

    return $drive->id;
}
// [END drive_create_drive]
require_once 'vendor/autoload.php';
createDrive();

