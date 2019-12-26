<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Formatter;

use App\Models\Emoji;
use Discuz\Cache\CacheManager;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Unparser;

class Formatter
{
    /**
     * @var CacheManager
     */
    protected $cache;

    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * @param CacheManager $cache
     * @param string $cacheDir
     */
    public function __construct(CacheManager $cache, $cacheDir)
    {
        $this->cache = $cache;
        $this->cacheDir = $cacheDir;
    }

    /**
     * Parse text.
     *
     * @param string $text
     * @param mixed $context
     * @return string
     */
    public function parse($text, $context = null)
    {
        $parser = $this->getParser($context);

        return $parser->parse($text);
    }

    /**
     * Render parsed XML.
     *
     * @param string $xml
     * @return string
     */
    public function render($xml)
    {
        $renderer = $this->getRenderer();

        return $renderer->render($xml);
    }

    /**
     * Unparse XML.
     *
     * @param string $xml
     * @return string
     */
    public function unparse($xml)
    {
        return Unparser::unparse($xml);
    }

    /**
     * Flush the cache so that the formatter components are regenerated.
     */
    public function flush()
    {
        $this->cache->forget('formatter');
    }

    /**
     * Generate thr formatter components cache.
     */
    public function cacheFormatter()
    {
        $formatter = $this->getConfigurator()->finalize();

        $this->cache->forever('formatter', $formatter);
    }

    /**
     * @return Configurator
     */
    protected function getConfigurator()
    {
        $configurator = new Configurator;

        $configurator->rootRules->enableAutoLineBreaks();

        $configurator->rendering->engine = 'PHP';
        $configurator->rendering->engine->cacheDir = $this->cacheDir;

        $configurator->Escaper;
        $configurator->Autoemail;
        $configurator->Autolink;
        $configurator->tags->onDuplicate('replace');

        // emoji
        foreach (Emoji::cursor() as $emoji) {
            $emojiImg = '<img src="' . $emoji->url . '" alt="' . $emoji->code . '">';
            $configurator->Emoticons->add($emoji->code, $emojiImg);
        }

        // html
        $configurator->HTMLElements->allowElement('blockquote');
        $configurator->HTMLElements->allowAttribute('blockquote', 'class');
        $configurator->HTMLElements->allowElement('span');
        $configurator->HTMLElements->allowAttribute('span', 'class');

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
        $formatter = $this->cache->get('formatter');

        if (! $formatter) {
            $this->cacheFormatter();
        }

        return $formatter[$name];
    }

    /**
     * Get the parser.
     *
     * @param mixed $context
     * @return \s9e\TextFormatter\Parser
     */
    protected function getParser($context = null)
    {
        $parser = $this->getComponent('parser');

        $parser->registeredVars['context'] = $context;

        return $parser;
    }

    /**
     * Get the renderer.
     *
     * @return \s9e\TextFormatter\Renderer
     */
    protected function getRenderer()
    {
        spl_autoload_register(function ($class) {
            if (file_exists($file = $this->cacheDir.'/'.$class.'.php')) {
                include $file;
            } else {
                $this->flush();

                $this->cacheFormatter();
            }
        });

        return $this->getComponent('renderer');
    }
}
