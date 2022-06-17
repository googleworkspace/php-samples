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

// [START slides_text_merging]
use Google\Client;
use Google\Service\Drive;
use Google\Service\Slides;
use Google\Service\Slides\Request;

function textMerging($templatePresentationId, $dataSpreadsheetId)
{

    /* Load pre-authorized user credentials from the environment.
       TODO(developer) - See https://developers.google.com/identity for
        guides on implementing OAuth2 for your application. */
    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
    $slidesService = new Google_Service_Slides($client);
    $driveService = new Google_Service_Drive($client);
    $sheetsService = new Google_Service_Sheets($client);
    try {
        $responses = array();
        // Use the Sheets API to load data, one record per row.
        $dataRangeNotation = 'Customers!A2:M6';
        $sheetsResponse =
            $sheetsService->spreadsheets_values->get($dataSpreadsheetId, $dataRangeNotation);
        $values = $sheetsResponse['values'];

        // For each record, create a new merged presentation.
        foreach ($values as $row) {
            $customerName = $row[2];     // name in column 3
            $caseDescription = $row[5];  // case description in column 6
            $totalPortfolio = $row[11];  // total portfolio in column 12

            // Duplicate the template presentation using the Drive API.
            $copy = new Google_Service_Drive_DriveFile(array(
                'name' => $customerName . ' presentation'
            ));
            $driveResponse = $driveService->files->copy($templatePresentationId, $copy);
            $presentationCopyId = $driveResponse->id;

            // Create the text merge (replaceAllText) requests for this presentation.
            $requests = array();
            $requests[] = new Google_Service_Slides_Request(array(
                'replaceAllText' => array(
                    'containsText' => array(
                        'text' => '{{customer-name}}',
                        'matchCase' => true
                    ),
                    'replaceText' => $customerName
                )
            ));
            $requests[] = new Google_Service_Slides_Request(array(
                'replaceAllText' => array(
                    'containsText' => array(
                        'text' => '{{case-description}}',
                        'matchCase' => true
                    ),
                    'replaceText' => $caseDescription
                )
            ));
            $requests[] = new Google_Service_Slides_Request(array(
                'replaceAllText' => array(
                    'containsText' => array(
                        'text' => '{{total-portfolio}}',
                        'matchCase' => true
                    ),
                    'replaceText' => $totalPortfolio
                )
            ));

            // Execute the requests for this presentation.
            $batchUpdateRequest = new Google_Service_Slides_BatchUpdatePresentationRequest(array(
                'requests' => $requests
            ));
            $response =
                $slidesService->presentations->batchUpdate($presentationCopyId, $batchUpdateRequest);
            $responses[] = $response;
            // Count the total number of replacements made.
            $numReplacements = 0;
            foreach ($response->getReplies() as $reply) {
                $numReplacements += $reply->getReplaceAllText()->getOccurrencesChanged();
            }
            printf("Created presentation for %s with ID: %s\n", $customerName, $presentationCopyId);
            printf("Replaced %d text instances.\n", $numReplacements);
        }
        return $responses;
    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }
}

// [END slides_text_merging]
require 'vendor/autoload.php';
textMerging('12ZqIbNsOdfGr99FQJi9mQ0zDq-Q9pdf6T3ReVBz0Lms', '1sN_EOj0aYp5hn9DeqSY72G7sKaFRg82CsMGnK_Tooa8');
