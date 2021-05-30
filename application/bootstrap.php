<?php

use Slim\Factory\AppFactory;

$containerBuilder = new \DI\ContainerBuilder();

//        $containerBuilder->addDefinitions([
//            \PoK\Core\ModuleModelManager\ModuleModelManager::class => function (ContainerInterface $c) {
//                return new \PoK\Core\ModuleModelManager\ModuleModelManager(
//                    new \PoK\Core\Entities\Model\ModelRepository(),
//                    new \PoK\Core\Entities\Model\ModelGateway(),
//                    new \PoK\Core\Entities\Model\ModelCompiler(),
//                    new \PoK\Core\Entities\ModelMigration\ModelMigrationRepository(),
//                    new \PoK\Core\Entities\ModelMigration\ModelMigrationGateway(),
//                    new \PoK\Core\Entities\ModelMigration\ModelMigrationCompiler(),
//                    new \PoK\Core\Entities\ModelTransformer\ModelTransformerRepository(),
//                    new \PoK\Core\Entities\ModelTransformer\ModelTransformerGateway(),
//                    new \PoK\Core\Entities\ModelTransformer\ModelTransformerCompiler()
//                );
//            },
//        ]);

$container = $containerBuilder->build();
AppFactory::setContainer($container);

if (!function_exists('project_path')) {
    function project_path($path = '') {
        return __DIR__.'/../'.$path;
    }
}

if (!function_exists('config')) {
    function config($string, $default = null) {
        $configpath = project_path('config_'.getenv('APPLICATION_ENV').'.php');
        return \PoK\Config\Config::get($configpath, $string, $default);
    }
}