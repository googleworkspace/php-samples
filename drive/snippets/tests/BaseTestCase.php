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
use Monolog\Logger;
use Google\Client;
use Google\Service\Docs;

class BaseTestCase extends PHPUnit_Framework_TestCase
{
    protected static $service;

    public static function setUpBeforeClass()
    {
        BaseTestCase::$service = self::createService();
    }

    protected function setUp()
    {
        $this->filesToDelete = array();
    }

    protected function tearDown()
    {
        if (sizeof($this->filesToDelete) > 0) {
            // Sleep for a second, to prevent file not found failures.
            sleep(1);
            foreach ($this->filesToDelete as $fileId) {
                self::$service->files->delete($fileId);
            }
        }
    }

    protected static function createService()
    {
        // create a log channel
        $log = new Logger('debug');
        $client = new Client();
        $client->setApplicationName('Google Drive API Snippet Tests');
        $client->useApplicationDefaultCredentials();
        $client->addScope('https://www.googleapis.com/auth/drive');
        $client->addScope('https://www.googleapis.com/auth/drive.appdata');
        $client->setLogger($log);
        return new Drive($client);
    }

    protected function deleteFileOnCleanup($id)
    {
        array_push($this->filesToDelete, $id);
    }

    protected function createTestDocument()
    {
        $fileMetadata = new Drive\DriveFile(array(
            'name' => 'Test document',
            'mimeType' => 'application/vnd.google-apps.document'));
        $content = file_get_contents('files/document.txt');
        $file = self::$service->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => 'text/plain',
            'uploadType' => 'multipart',
            'fields' => 'id'));
        $this->deleteFileOnCleanup($file->id);
        return $file->id;
    }

    protected function createTestBlob()
    {
        $fileMetadata = new Drive\DriveFile(array(
            'name' => 'photo.jpg'));
        $content = file_get_contents('files/photo.jpg');
        $file = self::$service->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => 'image/jpeg',
            'uploadType' => 'multipart',
            'fields' => 'id'));
        $this->deleteFileOnCleanup($file->id);
        return $file->id;
    }
}
