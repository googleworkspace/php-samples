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

// [START sheets_batch_get_values]

use Google\Client;
use Google\Service\Drive;
use Google\Service\Sheets;
/**
 * method to get a spreadsheet values in batch
 */

function batchGetValues($spreadsheetId)
    {
        /* Load pre-authorized user credentials from the environment.
        TODO(developer) - See https://developers.google.com/identity for
        guides on implementing OAuth2 for your application. */       
        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google\Service\Drive::DRIVE);
        $service = new Google_Service_Sheets($client);
        try{
            $ranges = 'Sheet1!A1:B2';
            $params = array(
                'ranges' => $ranges
            );
            //execute the request
            $result = $service->spreadsheets_values->batchGet($spreadsheetId, $params);
            printf("%d ranges retrieved.", count($result->getValueRanges()));
            return $result;
        }
        catch(Exception $e) {
            // TODO(developer) - handle error appropriately
            echo 'Message: ' .$e->getMessage();
          }
        }
        // [END sheets_batch_get_values]
        require 'vendor/autoload.php';
        batchGetValues('1sN_EOj0aYp5hn9DeqSY72G7sKaFRg82CsMGnK_Tooa8');
