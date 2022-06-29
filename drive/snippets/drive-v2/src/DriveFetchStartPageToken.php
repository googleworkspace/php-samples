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
 # [START drive_fetch_start_page_token]
# TODO - PHP client currently chokes on fetching start page token
use Google\Client;
use Google\Service\Drive;
function fetchStartPageToken()
{
    try {
        /* Load pre-authorized user credentials from the environment.
        TODO (developer) - See https://developers.google.com/identity for
         guides on implementing OAuth2 for your application. */
        $client = new Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $driveService = new Drive($client);
        $response = $driveService->changes->getStartPageToken();
        printf("Start token: %s\n", $response->startPageToken);
        return $response->startPageToken;
    } catch (Exception $e) {
        echo "Error Message: ". $e;
    }
    
}
// [END drive_fetch_start_page_token]
require_once 'vendor/autoload.php';
fetchStartPageToken();