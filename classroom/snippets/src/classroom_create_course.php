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

// [START classroom_create_course]
use Google\Service\Classroom\Course;

function createCourse($service)
{
    try {

        $course = new Google_Service_Classroom_Course(array(
            'name' => '10th Grade Biology',
            'section' => 'Period 2',
            'descriptionHeading' => 'Welcome to 10th Grade Biology',
            'description' => 'We\'ll be learning about about the structure of living ' .
                'creatures from a combination of textbooks, guest ' .
                'lectures, and lab work. Expect to be excited!',
            'room' => '301',
            'ownerId' => 'me',
            'courseState' => 'PROVISIONED'
        ));
        $course = $service->courses->create($course);
        printf("Course created: %s (%s)\n", $course->name, $course->id);
        return $course;
    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }
}

/* Load pre-authorized user credentials from the environment.
 TODO(developer) - See https://developers.google.com/identity for
  guides on implementing OAuth2 for your application. */
require 'vendor/autoload.php';
$client = new Google\Client();
$client->useApplicationDefaultCredentials();
$client->addScope("https://www.googleapis.com/auth/classroom.courses");
$service = new Google_Service_Classroom($client);
// [END classroom_create_course]
createCourse($service);
?>