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

function pivotTables($spreadsheetId)
{

    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
    $service = new Google_Service_Sheets($client);
    try {

        $requests = [
            new Google_Service_Sheets_Request([
                'addSheet' => [
                    'properties' => [
                        'title' => 'Sheet 1'
                    ]
                ]
            ]),
            new Google_Service_Sheets_Request([
                'addSheet' => [
                    'properties' => [
                        'title' => 'Sheet 2'
                    ]
                ]
            ])
        ];
        // Create two sheets for our pivot table
        $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
            'requests' => $requests
        ]);
        $batchUpdateResponse = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
        $sourceSheetId = $batchUpdateResponse->replies[0]->addSheet->properties->sheetId;
        $targetSheetId = $batchUpdateResponse->replies[1]->addSheet->properties->sheetId;
        // [START sheets_pivot_tables]
        $requests = [
            'updateCells' => [
                'rows' => [
                    'values' => [
                        [
                            'pivotTable' => [
                                'source' => [
                                    'sheetId' => $sourceSheetId,
                                    'startRowIndex' => 0,
                                    'startColumnIndex' => 0,
                                    'endRowIndex' => 20,
                                    'endColumnIndex' => 7
                                ],
                                'rows' => [
                                    [
                                        'sourceColumnOffset' => 1,
                                        'showTotals' => true,
                                        'sortOrder' => 'ASCENDING',
                                    ],
                                ],
                                'columns' => [
                                    [
                                        'sourceColumnOffset' => 4,
                                        'sortOrder' => 'ASCENDING',
                                        'showTotals' => true,
                                    ]
                                ],
                                'values' => [
                                    [
                                        'summarizeFunction' => 'COUNTA',
                                        'sourceColumnOffset' => 4
                                    ]
                                ],
                                'valueLayout' => 'HORIZONTAL'
                            ]
                        ]
                    ]
                ],
                'start' => [
                    'sheetId' => $targetSheetId,
                    'rowIndex' => 0,
                    'columnIndex' => 0
                ],
                'fields' => 'pivotTable'
            ]
        ];
        return $batchUpdateResponse;
    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }
}
// [END sheets_pivot_tables]
    pivotTables('1sN_EOj0aYp5hn9DeqSY72G7sKaFRg82CsMGnK_Tooa8');
    ?>