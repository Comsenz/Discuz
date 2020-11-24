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

class ThreadTest extends TestCase
{
    protected $thread;

    protected $threadResponse;

    protected $threadContent;

    protected $post;

    protected $postContent;

    protected $postResponse;

    public function setUp(): void
    {
        parent::setUp();
        $response = $this->http()->post('threads', [
            'json' => [
                'data' => [
                    'attributes' => [
                        'title' => 'TestCase Create Thread!',
                        'content' => 'hello world!',
                        'type' => 1,
                    ],
                    'relationships' => [
                        'category' => [
                            'data' => [
                                'type' => 'categories',
                                'id' => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $this->threadResponse = $response;
        $this->threadContent = $response->getBody()->getContents();
        $this->thread = json_decode($this->threadContent, true);

        $this->setPost();
    }

    public function testCreateThread()
    {
        $this->assertSame($this->threadResponse->getStatusCode(), 201);
        $this->assertStringContainsString('contentHtml', $this->threadContent);
    }

    public function testEditThread()
    {
        $response = $this->http()->patch('threads/' . Arr::get($this->thread, 'data.id'), [
            'json' => [
                'data' => [
                    'attributes' => [
                        'title' => 'TestCase Create Edit Thread!',
                    ],
                ],
            ],
        ]);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertStringContainsString('TestCase Create Edit Thread!', $response->getBody()->getContents());
    }

    public function testViewThread()
    {
        $response = $this->http()->get('threads/' . Arr::get($this->thread, 'data.id'));

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertStringContainsString('contentHtml', $response->getBody()->getContents());
    }

    public function testDeleteThread()
    {
        $response = $this->http()->delete('threads/' . Arr::get($this->thread, 'data.id'));
        $this->assertSame($response->getStatusCode(), 204);
    }

    /*
    |--------------------------------------------------------------------------
    | Post Test
    |--------------------------------------------------------------------------
    */

    public function setPost()
    {
        $included = collect(Arr::get($this->thread, 'included'));
        $post = $included->where('type', 'posts')->first();

        $response = $this->http()->post('posts', [
            'json' => [
                'data' => [
                    'attributes' => [
                        'replyId' => Arr::get($post, 'id'),
                        'content' => 'TestCase Create Reply Post!',
                        'is_comment' => true,
                    ],
                    'relationships' => [
                        'thread' => [
                            'data' => [
                                'type' => 'threads',
                                'id' => Arr::get($this->thread, 'data.id'),
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $this->postResponse = $response;
        $this->postContent = $response->getBody()->getContents();
        $this->post = json_decode($this->postContent, true);
    }

    public function testCreatePost()
    {
        $this->assertSame($this->postResponse->getStatusCode(), 201);
        $this->assertStringContainsString('contentHtml', $this->postContent);
    }

    public function testEditPost()
    {
        $response = $this->http()->patch('posts/' . Arr::get($this->post, 'data.id'), [
            'json' => [
                'data' => [
                    'attributes' => [
                        'content' => 'TestCase Edit Reply Post!',
                    ],
                ],
            ],
        ]);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertStringContainsString('contentHtml', $response->getBody()->getContents());
    }

    public function testViewPost()
    {
        $response = $this->http()->get('posts/' . Arr::get($this->post, 'data.id'));

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertStringContainsString('contentHtml', $response->getBody()->getContents());
    }

    public function testDeletePost()
    {
        $response = $this->http()->delete('posts/' . Arr::get($this->post, 'data.id'));
        $this->assertSame($response->getStatusCode(), 204);
    }

}
