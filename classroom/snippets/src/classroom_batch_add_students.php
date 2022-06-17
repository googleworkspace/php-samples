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

// [START classroom_batch_add_students]
use Google\Client;
use Google\Service\Classroom;
use Google\Service\Classroom\Student;
use Google\Service\Exception;

function batchAddStudents($courseId, $studentEmails)
{
    /* Load pre-authorized user credentials from the environment.
    TODO(developer) - See https://developers.google.com/identity for
     guides on implementing OAuth2 for your application. */
    $client = new Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope("https://www.googleapis.com/auth/classroom.profile.emails");
    $service = new Classroom($client);
    $service->getClient()->setUseBatch(true);
    //create batch
    $batch = $service->createBatch();
    foreach ($studentEmails as $studentEmail) {
        $student = new Student([
            'userId' => $studentEmail
        ]);
        $request = $service->courses_students->create($courseId, $student);
        $requestId = $studentEmail;
        $batch->add($request, $requestId);
    }
    //executing request
    $results = $batch->execute();
    foreach ($results as $responseId => $student) {
        $studentEmail = substr($responseId, strlen('response-') + 1);
        if ($student instanceof Exception) {
            $e = $student;
            printf("Error adding user '%s' to the course: %s\n", $studentEmail,
                $e->getMessage());
        } else {
            printf("User '%s' was added as a student to the course.\n",
                $student->profile->name->fullName, $courseId);
        }
    }
    $service->getClient()->setUseBatch(false);
    return $results;
}

// [END classroom_batch_add_students]
require 'vendor/autoload.php';
batchAddStudents('123456',['a', 'b']);