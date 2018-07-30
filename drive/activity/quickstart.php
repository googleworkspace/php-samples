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
// [START drive_activity_quickstart]
require __DIR__ . '/vendor/autoload.php';

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Drive Activity API Quickstart');
    $client->setScopes(implode(' ', array(
      Google_Service_Appsactivity::ACTIVITY,
      Google_Service_Drive::DRIVE_METADATA_READONLY)
    ));
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');

    // Load previously authorized credentials from a file.
    $credentialsPath = 'token.json';
    if (file_exists($credentialsPath)) {
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
    } else {
        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();
        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        $authCode = trim(fgets(STDIN));

        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // Check to see if there was an error.
        if (array_key_exists('error', $accessToken)) {
            throw new Exception(join(', ', $accessToken));
        }

        // Store the credentials to disk.
        if (!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
        printf("Credentials saved to %s\n", $credentialsPath);
    }
    $client->setAccessToken($accessToken);

    // Refresh the token if it's expired.
    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Appsactivity($client);

// Print the recent activity in your Google Drive.
$optParams = array(
    'source' => 'drive.google.com',
    'drive.ancestorId' => 'root',
    'pageSize' => 10,
);
$results = $service->activities->listActivities($optParams);

if (count($results->getActivities()) == 0) {
    print "No activity.\n";
} else {
    print "Recent activity:\n";
    foreach ($results->getActivities() as $activity) {
        $event = $activity->getCombinedEvent();
        $user = $event->getUser();
        $target = $event->getTarget();
        if (empty($user) || empty($target)) {
            continue;
        }
        $time = date(DateTime::RFC3339, $event->getEventTimeMillis() / 1000);
        printf("%s: %s, %s, %s (%s)\n", $time, $user->getName(),
                $event->getPrimaryEventType(), $target->getName(),
                $target->getMimeType());
    }
}
// [END drive_activity_quickstart]
