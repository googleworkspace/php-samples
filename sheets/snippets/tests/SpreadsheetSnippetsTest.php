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
require_once 'src/SpreadsheetSnippets.php';
require_once 'BaseTestCase.php';

class SpreadsheetSnippetsTest extends BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->snippets = new SpreadsheetSnippets(parent::$service);
    }

    public function testCreate()
    {
        $id = $this->snippets->create('Title');
        $this->assertNotNull($id, 'ID not returned.');
        $this->deleteFileOnCleanup($id);
    }

    public function testBatchUpdate()
    {
        $id = $this->createTestSpreadsheet();
        $this->populateValues($id);
        $response = $this->snippets->batchUpdate($id, 'New Title', 'Hello', 'Goodbye');
        $this->assertNotNull($response, 'Responses is null');
        $replies = $response->getReplies();
        $this->assertNotNull($replies, 'Replies is null');
        $this->assertEquals(2, count($replies), 'Missing replies');
        $findReplaceResponse = $replies[1]->getFindReplace();
        $this->assertNotNull($findReplaceResponse, 'Find/replace response is null');
        $this->assertEquals($findReplaceResponse->getValuesChanged(), 100, 'Wrong number of replacements');
    }

    public function testGetValues()
    {
        $id = $this->createTestSpreadsheet();
        $this->populateValues($id);
        $result = $this->snippets->getValues($id, 'A1:C2');
        $this->assertNotNull($result, 'No response returned.');
        $values = $result->getValues();
        $this->assertNotNull($values, 'No values returned.');
        $this->assertEquals(2, count($values), 'Wrong number of rows.');
        $this->assertEquals(3, count($values[0]), 'Wrong number of columns.');
    }

    public function testBatchGetValues()
    {
        $id = $this->createTestSpreadsheet();
        $this->populateValues($id);
        $result = $this->snippets->batchGetValues($id, array('A1:A3', 'B1:C1'));
        $this->assertNotNull($result, 'No response returned.');
        $valueRanges = $result->getValueRanges();
        $this->assertNotNull($valueRanges, 'No value ranges returned.');
        $this->assertEquals(2, count($valueRanges), 'Wrong number of value ranges.');
        $values = $valueRanges[0]->getValues();
        $this->assertNotNull($values, 'No values returned.');
        $this->assertEquals(3, count($values), 'Wrong number of rows.');
    }

    public function testUpdateValues()
    {
        $id = $this->createTestSpreadsheet();
        $result = $this->snippets->updateValues($id, 'A1:B2', 'USER_ENTERED', array(
            array('A', 'B'),
            array('C', 'D')
        ));
        $this->assertNotNull($result, 'No result returned.');
        $this->assertEquals(2, $result->getUpdatedRows(), 'Wrong number of rows updated.');
        $this->assertEquals(2, $result->getUpdatedColumns(), 'Wrong number of columns updated.');
        $this->assertEquals(4, $result->getUpdatedCells(), 'Wrong number of cells updated.');
    }

    public function testBatchUpdateValues()
    {
        $id = $this->createTestSpreadsheet();
        $result = $this->snippets->batchUpdateValues($id, 'A1:B2', 'USER_ENTERED', array(
            array('A', 'B'),
            array('C', 'D')
        ));
        $this->assertNotNull($result, 'No result returned.');
        $this->assertEquals(1, count($result->getResponses()), 'Wrong number of ranges updated.');
        $this->assertEquals(2, $result->getTotalUpdatedRows(), 'Wrong number of rows updated.');
        $this->assertEquals(2, $result->getTotalUpdatedColumns(), 'Wrong number of columns updated.');
        $this->assertEquals(4, $result->getTotalUpdatedCells(), 'Wrong number of cells updated.');
    }

    public function testAppendValues()
    {
        $id = $this->createTestSpreadsheet();
        $this->populateValues($id);
        $result = $this->snippets->appendValues($id, 'Sheet1', 'USER_ENTERED', array(
            array('A', 'B'),
            array('C', 'D')
        ));
        $this->assertNotNull($result, 'No result returned.');
        $this->assertEquals('Sheet1!A1:J10', $result->getTableRange(), 'Wrong table range appended to.');
        $updates = $result->getUpdates();
        $this->assertEquals(2, $updates->getUpdatedRows(), 'Wrong number of rows appended.');
        $this->assertEquals(2, $updates->getUpdatedColumns(), 'Wrong number of columns appended.');
        $this->assertEquals(4, $updates->getUpdatedCells(), 'Wrong number of cells appended.');
    }

    public function testPivotTables()
    {
        $id = $this->createTestSpreadsheet();
        $this->populateValues($id);
        $response = $this->snippets->pivotTables($id);
        $this->assertNotNull($response);
    }

    public function testConditionalFormatting()
    {
        $id = $this->createTestSpreadsheet();
        $this->populateValues($id);
        $response = $this->snippets->conditionalFormatting($id);
        $this->assertEquals(2, count($response->getReplies()));
    }
}
