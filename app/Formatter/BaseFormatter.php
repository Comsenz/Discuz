<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Formatter;

use App\Models\Emoji;
use App\Models\Post;
use App\Models\PostGoods;
use App\Models\Topic;
use App\Models\User;
use Discuz\Cache\CacheManager;
use Discuz\Foundation\Application;
use Discuz\Http\UrlGenerator;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Unparser;

class BaseFormatter
{
    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var CacheManager
     */
    protected $cache;

    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * @var User
     */
    protected static $actor;

    /**
     * @param UrlGenerator $url
     * @param CacheManager $cache
     * @param Application $app
     */
    public function __construct(UrlGenerator $url, CacheManager $cache, Application $app)
    {
        $this->url = $url;
        $this->cache = $cache;
        $this->cacheDir = $app->storagePath().'/formatter';
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

        $configurator->plugins->load('Escaper');
        $configurator->tags->onDuplicate('replace');

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

            $formatter = $this->cache->get('formatter');
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

    protected function confEmoji($configurator)
    {
        foreach (Emoji::cursor() as $emoji) {
            $url = $this->url->to('/' . $emoji->url);
            $emojiImg = '<img style="display:inline-block;height:20px;vertical-align:top;" src="' . $url . '" alt="' . trim($emoji->code, ':') . '" class="qq-emotion"/>';
            $configurator->Emoticons->add($emoji->code, $emojiImg);
        }
    }

    protected function confHtml($configurator)
    {
        $configurator->HTMLElements->allowElement('blockquote');
        $configurator->HTMLElements->allowAttribute('blockquote', 'class');
        $configurator->HTMLElements->allowElement('span');
        $configurator->HTMLElements->allowAttribute('span', 'class');
    }

    protected function confUserMention($configurator)
    {
        $tagName = 'USERMENTION';
        $tag = $configurator->tags->add($tagName);
        $tag->attributes->add('id');
        $tag->filterChain->prepend([static::class, 'addUserId']);
        $configurator->Preg->match('/\B@(?<username>.+)\s/i', $tagName);
    }

    protected function confTopic($configurator)
    {
        $tagName = 'TOPIC';
        $tag = $configurator->tags->add($tagName);
        $tag->attributes->add('id');
        $tag->filterChain->prepend([static::class, 'addTopicId']);
        $configurator->Preg->match('/\B#(?<topic>[\x{4e00}-\x{9fa5}\w]+)#/ui', $tagName);
    }

    /**
     * @param $tag
     *
     * @return bool
     */
    public static function addUserId($tag)
    {
        if ($user = User::where('username', $tag->getAttribute('username'))->first()) {
            $tag->setAttribute('id', $user->id);
            return true;
        }
    }

    /**
     * @param $tag
     * @return bool
     */
    public static function addTopicId($tag)
    {
        $topic = Topic::firstOrCreate(
            ['content' => $tag->getAttribute('topic')],
            ['user_id'=>static::$actor->id]
        );

        if ($topic) {
            $tag->setAttribute('id', $topic->id);
            return true;
        }
    }

    public static function setActor($actor)
    {
        static::$actor = $actor;
    }
}
