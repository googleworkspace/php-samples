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

// [START sheets_update_values]
use Google\Client;
use Google\Service\Drive;
use Google\Service\Sheets\ValueRange;


function updateValues($spreadsheetId, $range, $valueInputOption)
    {
        /* Load pre-authorized user credentials from the environment.
           TODO(developer) - See https://developers.google.com/identity for
            guides on implementing OAuth2 for your application. */
        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google\Service\Drive::DRIVE);
        $service = new Google_Service_Sheets($client);
        try{
        $values = [["sample", 'values']];

        $body = new Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);
        $params = [
            'valueInputOption' => $valueInputOption
        ];
        //executing the request
        $result = $service->spreadsheets_values->update($spreadsheetId, $range,
        $body, $params);
        printf("%d cells updated.", $result->getUpdatedCells());
        return $result;
    }
    catch(Exception $e) {
            // TODO(developer) - handle error appropriately
            echo 'Message: ' .$e->getMessage();
          }
    }
    // [END sheets_update_values]
    require 'vendor/autoload.php';
        updateValues('1sN_EOj0aYp5hn9DeqSY72G7sKaFRg82CsMGnK_Tooa8','Sheet1!A1:B2',"RAW");
