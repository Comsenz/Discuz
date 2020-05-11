<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Formatter;

use s9e\TextFormatter\Configurator;

class DialogMessageFormatter extends BaseFormatter
{
    /**
     * Flush the cache so that the formatter components are regenerated.
     */
    public function flush()
    {
        $this->cache->forget('dialogMessageFormatter');
    }

    /**
     * Generate thr formatter components cache.
     */
    public function cacheFormatter()
    {
        $formatter = $this->getConfigurator()->finalize();

        $this->cache->forever('dialogMessageFormatter', $formatter);
    }

    /**
     * @return Configurator
     */
    protected function getConfigurator()
    {

        $configurator = parent::getConfigurator();

        parent::confEmoji($configurator);

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
        $formatter = $this->cache->get('dialogMessageFormatter');

        if (! $formatter) {
            $this->cacheFormatter();

            $formatter = $this->cache->get('dialogMessageFormatter');
        }

        return $formatter[$name];
    }
}
