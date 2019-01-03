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
class SpreadsheetSnippets
{
    public function __construct($service)
    {
        $this->service = $service;
    }

    public function create($title)
    {
        $service = $this->service;
        // [START sheets_create]
        $spreadsheet = new Google_Service_Sheets_Spreadsheet([
            'properties' => [
                'title' => $title
            ]
        ]);
        $spreadsheet = $service->spreadsheets->create($spreadsheet, [
            'fields' => 'spreadsheetId'
        ]);
        printf("Spreadsheet ID: %s\n", $spreadsheet->spreadsheetId);
        // [END sheets_create]
        return $spreadsheet->spreadsheetId;
    }

    public function batchUpdate($spreadsheetId, $title, $find, $replacement)
    {
        $service = $this->service;
        // [START sheets_batch_update]
        $requests = [
          // Change the spreadsheet's title.
          new Google_Service_Sheets_Request([
              'updateSpreadsheetProperties' => [
                  'properties' => [
                      'title' => $title
                  ],
                  'fields' => 'title'
              ]
          ]),
          // Find and replace text.
          new Google_Service_Sheets_Request([
              'findReplace' => [
                  'find' => $find,
                  'replacement' => $replacement,
                  'allSheets' => true
              ]
          ])
        ];

        // Add additional requests (operations) ...
        $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
            'requests' => $requests
        ]);

        $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
        $findReplaceResponse = $response->getReplies()[1]->getFindReplace();
        printf("%s replacements made.\n",
        $findReplaceResponse->getOccurrencesChanged());
        // [END sheets_batch_update]
        return $response;
    }


    public function getValues($spreadsheetId, $range)
    {
        $service = $this->service;
        // [START sheets_get_values]
        $result = $service->spreadsheets_values->get($spreadsheetId, $range);
        $numRows = $result->getValues() != null ? count($result->getValues()) : 0;
        printf("%d rows retrieved.", $numRows);
        // [END sheets_get_values]
        return $result;
    }

    public function batchGetValues($spreadsheetId, $_ranges)
    {
        $service = $this->service;
        // [START sheets_batch_get_values]
        $ranges = [
            // Range names ...
        ];
        // [START_EXCLUDE silent]
        $ranges = $_ranges;
        // [END_EXCLUDE]
        $params = array(
            'ranges' => $ranges
        );
        $result = $service->spreadsheets_values->batchGet($spreadsheetId, $params);
        printf("%d ranges retrieved.", count($result->getValueRanges()));
        // [END sheets_batch_get_values]
        return $result;
    }

    public function updateValues($spreadsheetId, $range, $valueInputOption,
      $_values)
    {
        $service = $this->service;
        // [START sheets_update_values]
        $values = [
            [
                // Cell values ...
            ],
            // Additional rows ...
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
        $result = $service->spreadsheets_values->update($spreadsheetId, $range,
        $body, $params);
        printf("%d cells updated.", $result->getUpdatedCells());
        // [END sheets_update_values]
        return $result;
    }

    public function batchUpdateValues($spreadsheetId, $range, $valueInputOption,
      $_values)
    {
        $service = $this->service;
        // [START sheets_batch_update_values]
        $values = [
            [
                // Cell values ...
            ],
            // Additional rows ...
        ];
        // [START_EXCLUDE silent]
        $values = $_values;
        // [END_EXCLUDE]
        $data = [];
        $data[] = new Google_Service_Sheets_ValueRange([
            'range' => $range,
            'values' => $values
        ]);
        // Additional ranges to update ...
        $body = new Google_Service_Sheets_BatchUpdateValuesRequest([
            'valueInputOption' => $valueInputOption,
            'data' => $data
        ]);
        $result = $service->spreadsheets_values->batchUpdate($spreadsheetId, $body);
        printf("%d cells updated.", $result->getTotalUpdatedCells());
        // [END sheets_batch_update_values]
        return $result;
    }

    public function appendValues($spreadsheetId, $range, $valueInputOption,
      $_values)
    {
        $service = $this->service;
        // [START sheets_append_values]
        $values = [
            [
                // Cell values ...
            ],
            // Additional rows ...
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
        // [END sheets_append_values]
        return $result;
    }

    public function pivotTables($spreadsheetId)
    {
        $service = $this->service;
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
        // [END sheets_pivot_tables]
    }

    public function conditionalFormatting($spreadsheetId)
    {
        $service = $this->service;
        // [START sheets_conditional_formatting]
        $myRange = [
            'sheetId' => 0,
            'startRowIndex' => 1,
            'endRowIndex' => 11,
            'startColumnIndex' => 0,
            'endColumnIndex' => 4,
        ];

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
        // [END sheets_conditional_formatting]
    }
}
