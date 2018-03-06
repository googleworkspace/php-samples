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
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient() {
  $client = new Google_Client();
  $client->setApplicationName('Google Apps Script API PHP Quickstart');
  $client->setScopes("https://www.googleapis.com/auth/script.projects");
  $client->setAuthConfig('client_secret.json');
  $client->setAccessType('offline');

  // Load previously authorized credentials from a file.
  $credentialsPath = expandHomeDirectory('credentials.json');
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

    // Store the credentials to disk.
    if(!file_exists(dirname($credentialsPath))) {
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

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path) {
  $homeDirectory = getenv('HOME');
  if (empty($homeDirectory)) {
    $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
  }
  return str_replace('~', realpath($homeDirectory), $path);
}

/**
 * Shows basic usage of the Apps Script API.
 *
 * Call the Apps Script API to create a new script project, upload files to the
 * project, and log the script's URL to the user.
 */
$client = getClient();
$service = new Google_Service_Script($client);

// Create a management request object.
$request = new Google_Service_Script_CreateProjectRequest();
$request->setTitle('My Script');
$response = $service->projects->create($request);

$scriptId = $response->getScriptId();

$file1 = new Google_Service_Script_ScriptFile();
$file1->setName('hello');
$file1->setType('SERVER_JS');
$file1->setSource("function helloWorld() {\n  console.log(\"Hello, world!\"" +
                  ");\n}");

$file2 = new Google_Service_Script_ScriptFile();
$file2->setName('appsscript');
$file2->setType('JSON');
$file2->setSource("{\"timeZone\":\"America/New_York\",\"exceptionLogging\"" +
                  ":\"CLOUD\"}");

$request = new Google_Service_Script_Content();
$request->setScriptId($scriptId);
$request->setFiles([$file1, $file2]);

$response = $service->projects->updateContent($scriptId, $request);
echo 'https://script.google.com/d/' . $response->getScriptId() . '/edit';
