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

// [START sheets_append_values]
use Google\Client;
use Google\Service\Sheets;


function appendValues($spreadsheetId, $range, $valueInputOption)
{
    /* Load pre-authorized user credentials from the environment.
       TODO(developer) - See https://developers.google.com/identity for
        guides on implementing OAuth2 for your application. */
    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope('https://www.googleapis.com/auth/spreadsheets');
    $service = new Google\Service\Sheets($client);
    try {
        $values = []; //add the values to be appended
        //execute the request
        $body = new Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);
        $params = [
            'valueInputOption' => $valueInputOption
        ];
        $result = $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
        printf("%d cells appended.", $result->getUpdates()->getUpdatedCells());
        return $result;
    } catch (Exception $e) {
        // TODO(developer) - handle error appropriately
        echo 'Message: ' . $e->getMessage();
    }
    // [END sheets_append_values]
}

require 'vendor/autoload.php';
appendValues('1sN_EOj0aYp5hn9DeqSY72G7sKaFRg82CsMGnK_Tooa8', 'Sheet1!A1:B2', "RAW");
