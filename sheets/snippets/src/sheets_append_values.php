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

require __DIR__ . '/vendor/autoload.php';


// [START sheets_append_values]
function appendValues($spreadsheetId, $range, $valueInputOption,
      $_values)
{

    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
    $service = new Google_Service_Sheets($client);
    try {

        $values = [
            [
            ],
        ];
        // [START_EXCLUDE silent]
        $values = $_values;
        // [END_EXCLUDE]
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
        echo 'Message: ' . $e->getMessage();
    }
}
// [END sheets_append_values]
appendValues('1sN_EOj0aYp5hn9DeqSY72G7sKaFRg82CsMGnK_Tooa8', 'Sheet1!A1:B2', "RAW", []);

    ?>