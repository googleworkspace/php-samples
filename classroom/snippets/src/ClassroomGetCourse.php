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

// [START classroom_get_course]
use Google\Client;
use Google\Service\Classroom;
use Google\Service\Exception;

function getCourse($courseId)
{
    /* Load pre-authorized user credentials from the environment.
    TODO (developer) - See https://developers.google.com/identity for
     guides on implementing OAuth2 for your application. */
    $client = new Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope("https://www.googleapis.com/auth/classroom.courses");
    $service = new Classroom($client);
    try {
        $course = $service->courses->get($courseId);
        printf("Course '%s' found.\n", $course->name);
        return $course;
    } catch (Exception $e) {
        if ($e->getCode() == 404) {
            printf("Course with ID '%s' not found.\n", $courseId);
        } else {
            throw $e;
        }
    }
}

// [END classroom_get_course]

require 'vendor/autoload.php';
getCourse('123456');

