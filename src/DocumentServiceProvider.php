<?php

declare(strict_types=1);

namespace Rimba\Document;

use Rimba\Base\Services\BitesServiceProvider;

final class DocumentServiceProvider extends BitesServiceProvider
{
    protected string $iconsPath = __DIR__.'/../resources/svg';

    protected function registerPackage(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/document.php', 'document');
    }

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/document.php' => config_path('document.php'),
        ], 'document-config');
    }
}
