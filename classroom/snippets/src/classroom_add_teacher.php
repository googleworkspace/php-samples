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

// [START classroom_add_teacher]
use Google\Client;
use Google\Service\Classroom;
use Google\Service\Classroom\Teacher;
use Google\service\Exception;

function addTeacher($courseId, $teacherEmail)
{
    /* Load pre-authorized user credentials from the environment.
    TODO (developer) - See https://developers.google.com/identity for
     guides on implementing OAuth2 for your application. */
    $client = new Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope("https://www.googleapis.com/auth/classroom.profile.photos");
    $service = new Classroom($client);
    $teacher = new Teacher([
        'userId' => $teacherEmail
    ]);
    try {
        //  calling create teacher
        $teacher = $service->courses_teachers->create($courseId, $teacher);
        printf("User '%s' was added as a teacher to the course with ID '%s'.\n",
            $teacher->profile->name->fullName, $courseId);
    } catch (Exception $e) {
        if ($e->getCode() == 409) {
            printf("User '%s' is already a member of this course.\n", $teacherEmail);
        } else {
            throw $e;
        }
    }
    return $teacher;
}

// [END classroom_add_teacher]

require 'vendor/autoload.php';
addTeacher('531365794650','gduser2@workspacesamples.dev');
