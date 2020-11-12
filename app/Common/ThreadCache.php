<?php
/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Common;

class ThreadCache
{
    public $threads;
    public $metaLinks;

    /**
     * @param mixed $metaLinks
     */
    public function setMetaLinks($metaLinks): void
    {
        $this->metaLinks = $metaLinks;
    }

    /**
     * @return mixed
     */
    public function getMetaLinks()
    {
        return $this->metaLinks;
    }

    /**
     * @param mixed $threads
     */
    public function setThreads($threads): void
    {
        $this->threads = $threads;
    }

    /**
     * @return mixed
     */
    public function getThreads()
    {
        return $this->threads;
    }
}
