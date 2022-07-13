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
require 'src/SlidesCreateBulletedText.php';

class SlidesCreateBulletedTextTest extends \PHPUnit\Framework\TestCase
{
    
    public function testCreateBulletedTextText()
    {   
        $slideId = '1u_-382trVO_O_gsn-klkYZMXkEXtJ8YbbODH-IPoSoE';
        $textBoxId = 'MyTextBox_01';
        $presentation = createBulletedText($slideId, $textBoxId);
        $id = $presentation->presentationId;
        $this->assertNotNull($id, 'Missing presentation ID.');
    
    }
}