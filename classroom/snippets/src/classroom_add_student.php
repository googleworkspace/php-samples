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

// [START classroom_add_student]
use Google\Client;
use Google\Service\Classroom;
use Google\Service\Classroom\Student;
use Google\Service\Exception;

function enrollAsStudent($courseId,$enrollmentCode)
{
    /* Load pre-authorized user credentials from the environment.
    TODO (developer) - See https://developers.google.com/identity for
     guides on implementing OAuth2 for your application. */
    $client = new Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope("https://www.googleapis.com/auth/classroom.profile.emails");
    $service = new Classroom($client);
    $student = new Student([
        'userId' => 'me'
    ]);
    $params = [
        'enrollmentCode' => $enrollmentCode
    ];
    try {
        $student = $service->courses_students->create($courseId, $student, $params);
        printf("User '%s' was enrolled  as a student in the course with ID '%s'.\n",
            $student->profile->name->fullName, $courseId);
    } catch (Exception $e) {
        if ($e->getCode() == 409) {
            print "You are already a member of this course.\n";
        } else {
            throw $e;
        }
    }
    return $student;
}

// [END classroom_add_student]

require 'vendor/autoload.php';
enrollAsStudent( '123456','abcdef');
