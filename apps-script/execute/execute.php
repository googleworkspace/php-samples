<?php
/**
 * @license
 * Copyright Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
// [START apps_script_execute]
// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Script($client);

$scriptId = 'ENTER_YOUR_SCRIPT_ID_HERE';

// Create an execution request object.
$request = new Google_Service_Script_ExecutionRequest();
$request->setFunction('getFoldersUnderRoot');

try {
  // Make the API request.
  $response = $service->scripts->run($scriptId, $request);

  if ($response->getError()) {
    // The API executed, but the script returned an error.

    // Extract the first (and only) set of error details. The values of this
    // object are the script's 'errorMessage' and 'errorType', and an array of
    // stack trace elements.
    $error = $response->getError()['details'][0];
    printf("Script error message: %s\n", $error['errorMessage']);

    if (array_key_exists('scriptStackTraceElements', $error)) {
      // There may not be a stacktrace if the script didn't start executing.
      print "Script error stacktrace:\n";
      foreach($error['scriptStackTraceElements'] as $trace) {
        printf("\t%s: %d\n", $trace['function'], $trace['lineNumber']);
      }
    }
  } else {
    // The structure of the result will depend upon what the Apps Script
    // function returns. Here, the function returns an Apps Script Object
    // with String keys and values, and so the result is treated as a
    // PHP array (folderSet).
    $resp = $response->getResponse();
    $folderSet = $resp['result'];
    if (count($folderSet) == 0) {
      print "No folders returned!\n";
    } else {
      print "Folders under your root folder:\n";
      foreach($folderSet as $id => $folder) {
        printf("\t%s (%s)\n", $folder, $id);
      }
    }
  }
} catch (Exception $e) {
  // The API encountered a problem before the script started executing.
  echo 'Caught exception: ', $e->getMessage(), "\n";
}
// [END apps_script_execute]
