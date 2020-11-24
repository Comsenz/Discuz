<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *   http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Discuz\Tests\Api;

use Discuz\Tests\TestCase;
use Illuminate\Support\Arr;

class CategoryTest extends TestCase
{
    protected $category;

    protected $categoryResponse;

    protected $categoryContent;

    public function setUp(): void
    {
        parent::setUp();
        $response = $this->http()->post('categories', [
            'json' => [
                'data' => [
                    'attributes' => [
                        'name' => 'Category!',
                        'description' => 'This is Test Data',
                    ],
                ],
            ],
        ]);

        $this->categoryResponse = $response;
        $this->categoryContent = $response->getBody()->getContents();
        $this->category = json_decode($this->categoryContent, true);
    }

    public function testCreateCategory()
    {
        $this->assertSame($this->categoryResponse->getStatusCode(), 201);
        $this->assertStringContainsString('name', $this->categoryContent);
    }

    public function testEditCategory()
    {
        $response = $this->http()->patch('categories/' . Arr::get($this->category, 'data.id'), [
            'json' => [
                'data' => [
                    'attributes' => [
                        'name' => 'EditCate',
                    ],
                ],
            ],
        ]);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertStringContainsString('name', $response->getBody()->getContents());
    }

    public function testViewCategory()
    {
        $response = $this->http()->get('categories');

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertStringContainsString('name', $response->getBody()->getContents());
    }

    public function testDeleteCategory()
    {
        $response = $this->http()->delete('categories/' . Arr::get($this->category, 'data.id'));
        $this->assertSame($response->getStatusCode(), 204);
    }

}
