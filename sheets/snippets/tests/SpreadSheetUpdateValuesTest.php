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

 require 'src/sheets_update_values.php';

 class SpreadSheetUpdateValuesTest extends \PHPUnit\Framework\TestCase
 {
    public function testSpreadSheetUpdateValues()
    {   
        $spreadSheet = updateValues('1sN_EOj0aYp5hn9DeqSY72G7sKaFRg82CsMGnK_Tooa8','Sheet1!A1:B2',"RAW", []);
        $this->assertNotNull($spreadSheet, 'No result returned.');
    }
 }