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

// [START sheets_batch_update]
use Google\Client;
use Google\Service\Drive;
use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;

/**
 * to batch update a spreadsheet
 */
function batchUpdate($spreadsheetId, $title, $find, $replacement)
    {   
        /* Load pre-authorized user credentials from the environment.
           TODO(developer) - See https://developers.google.com/identity for
            guides on implementing OAuth2 for your application. */
        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google\Service\Drive::DRIVE);
        $service = new Google_Service_Sheets($client);
        try{
            //execute the request
            $requests = [
                new Google_Service_Sheets_Request([
              'updateSpreadsheetProperties' => [
                  'properties' => [
                      'title' => $title
                    ],
                    'fields' => 'title'
              ]
          ]),
          new Google_Service_Sheets_Request([
              'findReplace' => [
                  'find' => $find,
                  'replacement' => $replacement,
                  'allSheets' => true
                  ]
                  ])
                ];
                $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
                    'requests' => $requests
                ]);
        $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
        $findReplaceResponse = $response->getReplies()[1]->getFindReplace();
        printf("%s replacements made.\n",
        $findReplaceResponse->getOccurrencesChanged());
        return $response;
    }
    catch(Exception $e) {
        // TODO(developer) - handle error appropriately
        echo 'Message: ' .$e->getMessage();
      }
    }
    // [END sheets_batch_update]
    require 'vendor/autoload.php';
    batchUpdate('1sN_EOj0aYp5hn9DeqSY72G7sKaFRg82CsMGnK_Tooa8','title', 'abc', 'def');
