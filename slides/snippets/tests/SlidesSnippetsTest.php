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
require_once 'src/SlidesSnippets.php';
require_once 'BaseTestCase.php';

class SlidesSnippetsTest extends BaseTestCase
{
    const IMAGE_URL =
        'https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png';
    const TEMPLATE_PRESENTATION_ID = '1MmTR712m7U_kgeweE57POWwkEyWAV17AVAWjpmltmIg';
    const DATA_SPREADSHEET_ID = '14KaZMq2aCAGt5acV77zaA_Ps8aDt04G7T0ei4KiXLX8';
    const CHART_ID = 1107320627;
    const CUSTOMER_NAME = 'Fake Customer';

    protected function setUp()
    {
        parent::setUp();
        $this->snippets =
            new SlidesSnippets(parent::$service, parent::$driveService, parent::$sheetsService);
    }

    public function testCreatePresentation()
    {
        $presentation = $this->snippets->createPresentation('Title');
        $id = $presentation->presentationId;
        $this->assertNotNull($id, 'Missing presentation ID.');
        $this->deleteFileOnCleanup($id);
    }

    public function testCopyPresentation()
    {
        $presentationId = $this->createTestPresentation();
        $copyId = $this->snippets->copyPresentation($presentationId, "My Duplicate Presentation");
        $this->assertNotNull($copyId, 'Missing presentation copy ID.');
        $this->deleteFileOnCleanup($copyId);
    }

    public function testCreateSlide()
    {
        $presentationId = $this->createTestPresentation();
        $pageId = 'my_page_id';
        $response = $this->snippets->createSlide($presentationId, $pageId);
        $this->assertEquals(
            $pageId,
            $response->getReplies()[0]->getCreateSlide()->getObjectId(),
            'Unexpected Page ID.'
        );
    }

    public function testCreateTextboxWithText()
    {
        $presentationId = $this->createTestPresentation();
        $pageId = $this->createTestSlide($presentationId);
        $response = $this->snippets->createTextboxWithText($presentationId, $pageId);
        $this->assertEquals(2, sizeof($response->getReplies()), 'Unexpected number of replies.');
        $boxId = $response->getReplies()[0]->getCreateShape()->getObjectId();
        $this->assertNotNull($boxId, 'Missing textbox ID.');
    }

    public function testCreateImage()
    {
        $presentationId = $this->createTestPresentation();
        $pageId = $this->createTestSlide($presentationId);
        $response = $this->snippets->createImage($presentationId, $pageId);
        $this->assertEquals(1, sizeof($response->getReplies()), 'Unexpected number of replies.');
        $imageId = $response->getReplies()[0]->getCreateImage()->getObjectId();
        $this->assertNotNull($imageId, 'Missing image ID.');
    }

    public function testTextMerging()
    {
        $responses =
            $this->snippets->textMerging(self::TEMPLATE_PRESENTATION_ID, self::DATA_SPREADSHEET_ID);
        foreach ($responses as $response) {
            $presentationId = $response->presentationId;
            $this->assertNotNull($presentationId, 'Missing presentation ID.');
            $this->assertEquals(3, sizeof($response->getReplies()), 'Unexpected number of replies.');
            $numReplacements = 0;
            foreach ($response->getReplies() as $reply) {
                $numReplacements += $reply->getReplaceAllText()->getOccurrencesChanged();
            }
            $this->assertEquals(4, $numReplacements, 'Unexpected number of replacements.');
            $this->deleteFileOnCleanup($presentationId);
        }
    }

    public function testImageMerging()
    {
        $response = $this->snippets->imageMerging(
            self::TEMPLATE_PRESENTATION_ID,
            self::IMAGE_URL,
            self::CUSTOMER_NAME
        );
        $presentationId = $response->presentationId;
        $this->assertNotNull($presentationId, 'Missing presentation ID.');
        $this->assertEquals(2, sizeof($response->getReplies()), 'Unexpected number of replies.');
        $numReplacements = 0;
        foreach ($response->getReplies() as $reply) {
            $numReplacements += $reply->getReplaceAllShapesWithImage()->getOccurrencesChanged();
        }
        $this->assertEquals(2, $numReplacements, 'Unexpected number of replacements.');
        $this->deleteFileOnCleanup($presentationId);
    }

    public function testSimpleTextReplace()
    {
        $presentationId = $this->createTestPresentation();
        $pageId = $this->createTestSlide($presentationId);
        $boxId = $this->createTestTextbox($presentationId, $pageId);
        $response = $this->snippets->simpleTextReplace($presentationId, $boxId, 'MY NEW TEXT');
        $this->assertEquals(2, sizeof($response->getReplies()), 'Unexpected number of replies.');
    }

    public function testTextStyleUpdate()
    {
        $presentationId = $this->createTestPresentation();
        $pageId = $this->createTestSlide($presentationId);
        $boxId = $this->createTestTextbox($presentationId, $pageId);
        $response = $this->snippets->textStyleUpdate($presentationId, $boxId);
        $this->assertEquals(3, sizeof($response->getReplies()), 'Unexpected number of replies.');
    }

    public function testCreateBulletedText()
    {
        $presentationId = $this->createTestPresentation();
        $pageId = $this->createTestSlide($presentationId);
        $boxId = $this->createTestTextbox($presentationId, $pageId);
        $response = $this->snippets->createBulletedText($presentationId, $boxId);
        $this->assertEquals(1, sizeof($response->getReplies()), 'Unexpected number of replies.');
    }

    public function testCreateSheetsChart()
    {
        $presentationId = $this->createTestPresentation();
        $pageId = $this->createTestSlide($presentationId);
        $response = $this->snippets->createSheetsChart(
            $presentationId,
            $pageId,
            self::DATA_SPREADSHEET_ID,
            self::CHART_ID
        );
        $this->assertEquals(1, sizeof($response->getReplies()), 'Unexpected number of replies.');
        $chartId = $response->getReplies()[0]->getCreateSheetsChart()->getObjectId();
        $this->assertNotNull($chartId, 'Missing chart ID.');
    }

    public function testRefreshSheetsChart()
    {
        $presentationId = $this->createTestPresentation();
        $pageId = $this->createTestSlide($presentationId);
        $chartId = $this->createSheetsChart(
            $presentationId,
            $pageId,
            self::DATA_SPREADSHEET_ID,
            self::CHART_ID
        );
        $response = $this->snippets->refreshSheetsChart($presentationId, $chartId);
        $this->assertEquals(1, sizeof($response->getReplies()), 'Unexpected number of replies.');
    }
}
