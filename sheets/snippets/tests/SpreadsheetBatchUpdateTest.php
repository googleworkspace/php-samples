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

 require 'src/SpreadsheetBatchUpdate.php';

 class SpreadsheetBatchUpdateTest extends \PHPUnit\Framework\TestCase
 {
    public function testSpreadSheetBatchUpdate()
    {   
      $spreadSheet = batchUpdate('1ouP91n5RTHJLBlgYdTkHCWw5McOYUxyRvRNJtvuL0zw','Sample sheet for uts', 'abc', 'def');
      $this->assertNotNull($spreadSheet, "spread sheet is empty no response !!");
      $replies = $spreadSheet->getReplies();
      $this->assertNotNull($replies, 'Replies is null');
      $this->assertEquals(2, count($replies), 'Missing replies');
      $findReplaceResponse = $replies[1]->getFindReplace();
      $this->assertNotNull($findReplaceResponse, 'Find/replace response is null');
      print_r($findReplaceResponse->getValuesChanged());
    }
 }