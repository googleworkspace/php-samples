<?php
/**
 * Copyright 2018 Google LLC.
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

use PHPUnit\Framework\TestCase;

class snippetsTest extends TestCase
{
    // Document ID for testing
    private static $documentId = '195j9eDD3ccgjQRttHhJPymLJUCOUjs-jmwTrekvdjFE';

    public function setUp()
    {
        if (!file_exists($credsFile = __DIR__ . '/../credentials.json')) {
            $this->markTestSkipped(sprintf('Save your client credentials to %s', $credsFile));
        }
    }

    public function testExtractTest()
    {
        $output = $this->runSnippet('extract_text', [self::$documentId]);
        $this->assertNotContains('Docs API Quickstart', $output);
        $this->assertContains('Sample doc', $output);
    }

    public function testOutputAsJson()
    {
        $output = $this->runSnippet('output_as_json', [self::$documentId]);
        $document = json_decode($output, true);
        $this->assertArrayHasKey('title', $document);
        $this->assertEquals('Docs API Quickstart', $document['title']);
    }

    private function runSnippet($sampleName, $params = [])
    {
        $argv = array_merge([0], $params);
        ob_start();
        require __DIR__ . "/../$sampleName.php";
        return ob_get_clean();
    }
}
