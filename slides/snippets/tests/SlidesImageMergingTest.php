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
require 'src/slides_image_merging.php';

class SlidesImageMergingTest extends \PHPUnit\Framework\TestCase
{
    
    public function testImageMerging()
    {
        $presentation = imageMerging('1nPQcu0kBzDfveThvi8CQLLEW70CwO6jdv0Dn2GLEoyU', 'https://www.google.com/images/branding/'
        . 'googlelogo/2x/googlelogo_color_272x92dp.png', "kirmada");
        $id = $presentation;
        $this->assertNotNull($id, 'Missing presentation ID.');
    }
}