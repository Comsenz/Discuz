<?php

namespace App\Emoji;

use App\Models\Emoji;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Configurator\Bundle as AbstractBundleConfigurator;

class EmojiBundleConfigurator extends AbstractBundleConfigurator
{
    public function configure(Configurator $configurator): void
    {
        // TODO: emoji absolute url

        // Configure plugins
        foreach (Emoji::cursor() as $emoji) {
            $emojiImg = '<img src="' . $emoji->url . '" alt="' . $emoji->code . '">';
            $configurator->Emoticons->add($emoji->code, $emojiImg);
        }

        // Configure the PHP renderer to exist in the current namespace
        $configurator->rendering->engine            = 'PHP';
        $configurator->rendering->engine->className = __NAMESPACE__ . '\\Renderer';
        $configurator->rendering->engine->filepath  = __DIR__ . '/Renderer.php';
    }

    public static function saveBundle(): bool
    {
        $configurator = (new static)->getConfigurator();

        return $configurator->saveBundle(
            __NAMESPACE__ . '\\Bundle',
            __DIR__ . '/Bundle.php',
            ['autoInclude' => false]
        );
    }
}
