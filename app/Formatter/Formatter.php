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

namespace App\Formatter;

use s9e\TextFormatter\Configurator;

class Formatter extends BaseFormatter
{
    /**
     * @return Configurator
     */
    protected function getConfigurator()
    {
        $configurator = parent::getConfigurator();

        parent::confEmoji($configurator);

        parent::confHtml($configurator);

        parent::confUserMention($configurator);

        parent::confTopic($configurator);

        $configurator->plugins->load('Litedown');
        $configurator->plugins->load('TaskLists');
        $configurator->plugins->load('PipeTables');

        return $configurator;
    }
}
