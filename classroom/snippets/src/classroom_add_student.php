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
require __DIR__ . '/vendor/autoload.php';

function enrollAsStudent($service, $courseId, $enrollmentCode) {
  $student = new Google_Service_Classroom_Student(array(
    'userId' => 'me'
  ));
  $params = array(
    'enrollmentCode' => $enrollmentCode
  );
  try {
    $student = $service->courses_students->create($courseId, $student, $params);
    printf("User '%s' was enrolled  as a student in the course with ID '%s'.\n",
        $student->profile->name->fullName, $courseId);
  } catch (Google_Service_Exception $e) {
    if ($e->getCode() == 409) {
      print "You are already a member of this course.\n";
    } else {
      throw $e;
    }
  }
  return $student;
}

/* Load pre-authorized user credentials from the environment.
 TODO(developer) - See https://developers.google.com/identity for
  guides on implementing OAuth2 for your application. */
$client = new Google\Client();
$client->useApplicationDefaultCredentials();
$client->addScope("https://www.googleapis.com/auth/classroom.profile.emails");
$service = new Google_Service_Classroom($client);
// [END classroom_add_student]

enrollAsStudent($service, '531365794650' ,'gduser1@workspacesamples.dev');
?>