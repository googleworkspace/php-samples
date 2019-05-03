<?php
/**
 * Copyright 2019 Google Inc.
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
// [START drive_activity_v2_quickstart]
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
    $client->setScopes(Google_Service_DriveActivity::DRIVE_ACTIVITY_READONLY);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_DriveActivity($client);

// Print the recent activity in your Google Drive.
$request = new Google_Service_DriveActivity_QueryDriveActivityRequest();
$request->setPageSize(10);
$results = $service->activity->query($request);

if (count($results->getActivities()) == 0) {
    print "No activity.\n";
} else {
    print "Recent activity:\n";
    foreach ($results->getActivities() as $activity) {
        $time = getTimeInfo($activity);
        $action = getActionInfo($activity->getPrimaryActionDetail());
        $actors = array_map("getActorInfo", $activity->getActors());
        $targets = array_map("getTargetInfo", $activity->getTargets());
        printf("%s: %s, %s, %s\n", $time, truncated($actors), $action, truncated($targets));
    }
}

// Returns a string representation of the first elements in a list.
function truncated($array, $limit = 2)
{
    $contents = implode(', ', array_slice($array, 0, $limit));
    $more = count($array) > $limit ? ', ...' : '';
    return sprintf('[%s%s]', $contents, $more);
}

// Returns the name of a set property in an object, or else "unknown".
function getOneOf($obj)
{
    foreach ($obj as $key => $val) {
        return $key;
    }
    return 'unknown';
}

// Returns a time associated with an activity.
function getTimeInfo($activity)
{
    if ($activity->getTimestamp() != null) {
        return $activity->getTimestamp();
    }
    if ($activity->getTimeRange() != null) {
        return $activity->getTimeRange()->getEndTime();
    }
    return 'unknown';
}

// Returns the type of action.
function getActionInfo($actionDetail)
{
    return getOneOf($actionDetail);
}

// Returns user information, or the type of user if not a known user.
function getUserInfo($user)
{
    if ($user->getKnownUser() != null) {
        $knownUser = $user->getKnownUser();
        $isMe = $knownUser->getIsCurrentUser();
        return $isMe ? "people/me" : $knownUser->getPersonName();
    }
    return getOneOf($user);
}

// Returns actor information, or the type of actor if not a user.
function getActorInfo($actor)
{
    if ($actor->getUser() != null) {
        return getUserInfo($actor->getUser());
    }
    return getOneOf($actor);
}

// Returns the type of a target and an associated title.
function getTargetInfo($target)
{
    if ($target->getDriveItem() != null) {
        return sprintf('driveItem:"%s"', $target->getDriveItem()->getTitle());
    }
    if ($target->getDrive() != null) {
        return sprintf('drive:"%s"', $target->getDrive()->getTitle());
    }
    if ($target->getFileComment() != null) {
        $parent = $target->getFileComment()->getParent();
        if ($parent != null) {
            return sprintf('fileComment:"%s"', $parent->getTitle());
        }
        return 'fileComment:unknown';
    }
    return getOneOf($target);
}
// [END drive_activity_v2_quickstart]
