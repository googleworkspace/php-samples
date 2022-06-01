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

// [START sheets_batch_get_values]
function batchGetValues($spreadsheetId, $_ranges)
{
    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
    $service = new Google_Service_Sheets($client);
    try {

        $ranges = [];
        $ranges = $_ranges;
        $params = array(
            'ranges' => $ranges
        );
        $result = $service->spreadsheets_values->batchGet($spreadsheetId, $params);
        printf("%d ranges retrieved.", count($result->getValueRanges()));
        return $result;
    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }

}
// [END sheets_batch_get_values]
    batchGetValues('1sN_EOj0aYp5hn9DeqSY72G7sKaFRg82CsMGnK_Tooa8', 'Sheet1!A1:B2');

    ?>