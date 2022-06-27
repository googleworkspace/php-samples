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
 * 
 */

 require 'src/classroom_createCourse.php';

 class ClassroomCreateCourseTest extends \PHPUnit\Framework\TestCase
 {
    protected function getService()
    {
        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope("https://www.googleapis.com/auth/classroom.courses");
        $service = new Google_Service_Classroom($client);
        return $service;
    }

    public function testCreateCourse()
    {
        $classroomResponse = createCourse(getService());
        $this->assertNotNull($classroomResponse, "Not get any value from service");
    }


 }

