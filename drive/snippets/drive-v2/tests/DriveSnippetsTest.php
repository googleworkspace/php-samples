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
require_once 'src/DriveSnippets.php';
require_once 'BaseTestCase.php';

class DriveSnippetsTest extends BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->snippets = new DriveSnippets(parent::$service);
    }

    public function testUploadBasic()
    {
        $id = $this->snippets->uploadBasic();
        $this->assertNotNull($id, 'ID not returned.');
        $this->deleteFileOnCleanup($id);
    }

    public function testUploadToFolder()
    {
        $folderId = $this->snippets->createFolder();
        $this->deleteFileOnCleanup($folderId);
        $id = $this->snippets->uploadToFolder($folderId);
        $this->assertNotNull($id, 'ID not returned.');
        $this->deleteFileOnCleanup($id);
    }

    public function testUploadWithConversion()
    {
        $id = $this->snippets->uploadWithConversion();
        $this->assertNotNull($id, 'ID not returned.');
        $this->deleteFileOnCleanup($id);
    }

    public function testExportPdf()
    {
        $id = $this->createTestDocument();
        $content = $this->snippets->exportPdf($id);
        $this->assertNotNull($content, 'Content not returned.');
        $this->assertEquals('%PDF', substr($content, 0, 4));
    }

    public function testDownloadFile()
    {
        $id = $this->createTestBlob();
        $content = $this->snippets->downloadFile($id);
        $this->assertNotNull($content, 'Content not returned.');
        $this->assertEquals(0xFF, ord($content[0]));
        $this->assertEquals(0xD8, ord($content[1]));
    }

    public function testCreateShortcut()
    {
        $id = $this->snippets->createShortcut();
        $this->assertNotNull($id, 'ID not returned.');
        $this->deleteFileOnCleanup($id);
    }

    public function testTouchFile()
    {
        $id = $this->createTestBlob();
        $now = date('Y-m-d\TH:i:s.uP');
        $modifiedTime = $this->snippets->touchFile($id, $now);
        $this->assertEquals(strtotime($now), strtotime($modifiedTime));
    }

    public function testCreateFolder()
    {
        $id = $this->snippets->createFolder();
        $this->assertNotNull($id, 'ID not returned.');
        $this->deleteFileOnCleanup($id);
    }

    public function testMoveFileToFolder()
    {
        $folderId = $this->snippets->createFolder();
        $fileId = $this->createTestBlob();
        $parents = $this->snippets->moveFileToFolder($fileId, $folderId);
        $this->deleteFileOnCleanup($folderId);
        $this->assertContains($folderId, $parents);
        $this->assertEquals(1, count($parents));
    }

    public function testSearchFiles()
    {
        $id = $this->createTestBlob();
        $files = $this->snippets->searchFiles();
        $this->assertNotEquals(0, count($files));
    }

    public function testShareFile()
    {
        $id = $this->createTestBlob();
        $ids = $this->snippets->shareFile($id,
            'user@test.appsdevtesting.com',
            'test.appsdevtesting.com');
        $this->assertEquals(2, count($ids));
    }

    public function testUploadAppData()
    {
        $id = $this->snippets->uploadAppData();
        $this->assertNotNull($id, 'ID not returned.');
        $this->deleteFileOnCleanup($id);
    }

    public function testListAppData()
    {
        $id = $this->snippets->uploadAppData();
        $files = $this->snippets->listAppData();
        $this->deleteFileOnCleanup($id);
        $this->assertNotEquals(0, count($files));
    }

    public function testfetchAppDataFolder()
    {
        $id = $this->snippets->fetchAppDataFolder();
        $this->assertNotNull($id, 'ID not returned.');
    }

    public function testFetchStartPageToken()
    {
        $token = $this->snippets->fetchStartPageToken();
        $this->assertNotNull($token, 'Token not returned.');
    }

    public function testFetchChanges()
    {
        $startToken = $this->snippets->fetchStartPageToken();
        $id = $this->createTestBlob();
        usleep(500000);
        $token = $this->snippets->fetchChanges($startToken);
        $this->assertNotNull($token, 'Token not returned.');
        $this->assertNotEquals((string)$startToken, $token);
    }
}
