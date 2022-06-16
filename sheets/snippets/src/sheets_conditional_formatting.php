
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

// [START sheets_conditional_formatting]
use Google\Client;
use Google\Service\Drive;
use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;
use Google\Service\Sheets\Request;

function conditionalFormatting($spreadsheetId)
    {
        /* Load pre-authorized user credentials from the environment.
           TODO(developer) - See https://developers.google.com/identity for
            guides on implementing OAuth2 for your application. */
        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google\Service\Drive::DRIVE);
        $service = new Google_Service_Sheets($client);

        try{
            $myRange = [
                'sheetId' => 0,
                'startRowIndex' => 1,
                'endRowIndex' => 11,
                'startColumnIndex' => 0,
                'endColumnIndex' => 4,
            ];
            //execute the request
            $requests = [
                new Google_Service_Sheets_Request([
                'addConditionalFormatRule' => [
                    'rule' => [
                        'ranges' => [ $myRange ],
                        'booleanRule' => [
                            'condition' => [
                                'type' => 'CUSTOM_FORMULA',
                                'values' => [ [ 'userEnteredValue' => '=GT($D2,median($D$2:$D$11))' ] ]
                            ],
                            'format' => [
                                'textFormat' => [ 'foregroundColor' => [ 'red' => 0.8 ] ]
                                ]
                                ]
                            ],
                            'index' => 0
                            ]
                        ]),
                        new Google_Service_Sheets_Request([
                'addConditionalFormatRule' => [
                    'rule' => [
                        'ranges' => [ $myRange ],
                        'booleanRule' => [
                            'condition' => [
                                'type' => 'CUSTOM_FORMULA',
                                'values' => [ [ 'userEnteredValue' => '=LT($D2,median($D$2:$D$11))' ] ]
                            ],
                            'format' => [
                                'backgroundColor' => [ 'red' => 1, 'green' => 0.4, 'blue' => 0.4 ]
                            ]
                            ]
                        ],
                    'index' => 0
                ]
                ])
        ];
        
        $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
            'requests' => $requests
        ]);
        $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
        printf("%d cells updated.", count($response->getReplies()));
        return $response;
    }
    catch(Exception $e) {
        // TODO(developer) - handle error appropriately
        echo 'Message: ' .$e->getMessage();
    }
}
    // [END sheets_conditional_formatting]
    require 'vendor/autoload.php';
    conditionalFormatting('1sN_EOj0aYp5hn9DeqSY72G7sKaFRg82CsMGnK_Tooa8');
    ?>