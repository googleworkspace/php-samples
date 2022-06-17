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

// [START classroom_list_courses]
use Google\Service\Classroom;
use Google\Client;

function listCourses(): array
{
    /* Load pre-authorized user credentials from the environment.
    TODO (developer) - See https://developers.google.com/identity for
     guides on implementing OAuth2 for your application. */
    $client = new Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope("https://www.googleapis.com/auth/classroom.courses");
    $service = new Classroom($client);
    $courses = [];
    $pageToken = '';

    do {
        $params = [
            'pageSize' => 100,
            'pageToken' => $pageToken
        ];
        $response = $service->courses->listCourses($params);
        $courses = array_merge($courses, $response->courses);
        $pageToken = $response->nextPageToken;
    } while (!empty($pageToken));

    if (count($courses) == 0) {
        print "No courses found.\n";
    } else {
        print "Courses:\n";
        foreach ($courses as $course) {
            printf("%s (%s)\n", $course->name, $course->id);
        }
    }
    return $courses;
}

// [END classroom_list_courses]
require 'vendor/autoload.php';
listCourses();

