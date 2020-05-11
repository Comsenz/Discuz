<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Formatter;

use s9e\TextFormatter\Configurator;

class MarkdownFormatter extends BaseFormatter
{
    /**
     * Flush the cache so that the formatter components are regenerated.
     */
    public function flush()
    {
        $this->cache->forget('markdownFormatter');
    }

    /**
     * Generate thr formatter components cache.
     */
    public function cacheFormatter()
    {
        $formatter = $this->getConfigurator()->finalize();

        $this->cache->forever('markdownFormatter', $formatter);
    }

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

        return $configurator;
    }

    /**
     * Get a TextFormatter component.
     *
     * @param string $name "renderer" or "parser" or "js"
     * @return mixed
     */
    protected function getComponent($name)
    {
        $formatter = $this->cache->get('markdownFormatter');

        if (! $formatter) {
            $this->cacheFormatter();

            $formatter = $this->cache->get('markdownFormatter');
        }

        return $formatter[$name];
    }
}
