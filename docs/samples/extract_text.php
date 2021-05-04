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

# [START docs_extract_text]
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

/**
 * Returns the text in the given ParagraphElement.
 *
 * @param $element a ParagraphElement from a Google Doc.
 */
function read_paragraph_element($element)
{
    if (!$textRun = $element->getTextRun()) {
        return '';
    }
    return $textRun->content;
}

/**
 * Securses through a list of Structural Elements to read a document's text where text may be
 * in nested elements.
 *
 * @param $elements A list of Structural Elements.
 */
function read_structural_elements($elements)
{
    $text = '';
    foreach ($elements as $value) {
        if ($paragraph = $value->getParagraph()) {
            $elements = $paragraph->getElements();
            foreach ($elements as $element) {
                $text .= read_paragraph_element($element);
            }
        } elseif ($table = $value->getTable()) {
            // The text in table cells are nested in Structural Elements and tables may be nested.
            foreach ($table->getTableRows() as $row) {
                foreach ($row->getTableCells() as $cell) {
                    $text .= read_structural_elements($cell->getContent());
                }
            }
        } elseif ($toc = $value->getTableOfContents()) {
            $text .= read_structural_elements($toc->getContent());
        }
    }

    return $text;
}

// Fetch the document and print all text elements
$service = new Google_Service_Docs($client);
$doc = $service->documents->get($documentId);
echo read_structural_elements($doc->getBody()->getContent());
# [END docs_extract_text]
