<?php
/**
 * Copyright 2019 Google LLC.
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

/**
 * For instructions on how to run the full sample:
 *
 * @see https://github.com/googleworkspace/php-samples/blob/master/docs/samples/README.md
 */

// Include Google Cloud dependendencies using Composer
require_once __DIR__ . '/vendor/autoload.php';

if (count($argv) != 2) {
    return printf("Usage: php %s DOCUMENT_ID\n", basename(__FILE__));
}
list($_, $documentId) = $argv;

# [START docs_output_as_json]
// $documentId = 'YOUR_DOCUMENT_ID';

/**
 * Create an authorized API client.
 * Be sure you've set up your OAuth2 consent screen at
 * https://console.cloud.google.com/apis/credentials/consent
 */
$client = new Google_Client();
$client->setScopes(Google_Service_Docs::DOCUMENTS_READONLY);
$client->setAuthConfig('credentials.json');
$client->setAccessType('offline');

// You can use any PSR-6 compatible cache
$fileCache = new duncan3dc\Cache\FilesystemPool(__DIR__);

$fetchAccessTokenFunc = function() use ($client, $fileCache) {
    // Load previously authorized credentials from a file.
    if ($accessToken = $fileCache->get('access_token')) {
        return $accessToken;
    }
    // Request authorization from the user.
    $authUrl = $client->createAuthUrl();
    printf("Open the following link in your browser:\n%s\n", $authUrl);
    print 'Enter verification code: ';
    $authCode = trim(fgets(STDIN));

    // Exchange authorization code for an access token.
    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
    $fileCache->set('access_token', $accessToken);

    return $accessToken;
};

// Load the access token into the client
$client->setAccessToken($fetchAccessTokenFunc());

// Refresh the token if it's expired.
if ($client->isAccessTokenExpired()) {
    $accessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    $fileCache->set('access_token', $accessToken);
}

// Fetch the document and print the results as formatted JSON
$service = new Google_Service_Docs($client);
$doc = $service->documents->get($documentId);
print(json_encode((array) $doc, JSON_PRETTY_PRINT));
# [END docs_output_as_json]
