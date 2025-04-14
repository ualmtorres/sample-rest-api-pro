<?php

namespace App\Infrastructure\Bootstrap;

use DI\ContainerBuilder;
use Slim\App;
use Slim\Factory\AppFactory;

class Bootstrap
{
    private App $app;

    public function __construct()
    {
        $this->initializeContainer();
    }

    private function initializeContainer(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(dirname(__DIR__, 3) . '/config/container.php');

        AppFactory::setContainer($builder->build());
        $this->app = AppFactory::create();

        $this->configureApp();
    }

    private function configureApp(): void
    {
        $this->app->addBodyParsingMiddleware();
        $this->app->addErrorMiddleware(true, true, true);
    }

    public function getApp(): App
    {
        return $this->app;
    }
}
