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

// [START classroom_patch_course]
require __DIR__ . '/vendor/autoload.php';

function patchCourse($service, $courseId)
{
    try {

        $course = new Google_Service_Classroom_Course(array(
            'section' => 'Period 3',
            'room' => '302'
        ));
        $params = array(
            'updateMask' => 'section,room');
        $course = $service->courses->patch($courseId, $course, $params);
        printf("Course '%s' updated.\n", $course->name);
        return $course;
    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }
}

/* Load pre-authorized user credentials from the environment.
 TODO(developer) - See https://developers.google.com/identity for
  guides on implementing OAuth2 for your application. */
$client = new Google\Client();
$client->useApplicationDefaultCredentials();
$client->addScope("https://www.googleapis.com/auth/classroom.courses");
$service = new Google_Service_Classroom($client);
// [END classroom_patch_course]

patchCourse($service,'531365683519');
?>