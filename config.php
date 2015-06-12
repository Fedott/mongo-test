<?php
$localConfig = [];

if (file_exists(__DIR__."/config.local.php")) {
    $localConfig = include __DIR__."/config.local.php";
}

return $localConfig + [
    'db.host' => 'mongodb://10.10.10.2:27017,10.10.10.3:27017,10.10.10.4:27017',
    'db.options' => ['replicaSet' => 'mongo-test'],
    'db.database' => 'mongo-test',
    'doctrine.proxyDir' => __DIR__.'/cache/proxies',
    'doctrine.proxyNamespace' => 'Proxies',
    'doctrine.hydratorDir' => __DIR__.'/cache/hydrators',
    'doctrine.hydratorNamespace' => 'Hydrators',
    'doctrine.documentClassesPath' => __DIR__.'/src/Fedot/MongoTest/Model',

    'doctrine.documentManager' => DI\factory(function (DI\Container $c) {
        return \Doctrine\ODM\MongoDB\DocumentManager::create(
            $c->get('doctrine.connection'),
            $c->get('doctrine.configuration')
        );
    }),

    'doctrine.annotationDriver' => DI\factory(function(DI\Container $c) {
        \Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver::registerAnnotationClasses();
        return \Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver::create($c->get('doctrine.documentClassesPath'));
    }),

    'doctrine.connection' => DI\object(Doctrine\MongoDB\Connection::class)
        ->constructor(DI\link('db.host'), DI\link('db.options')),

    'doctrine.configuration' => DI\object(Doctrine\ODM\MongoDB\Configuration::class)
        ->method('setProxyDir', DI\link('doctrine.proxyDir'))
        ->method('setProxyNamespace', DI\link('doctrine.proxyNamespace'))
        ->method('setHydratorDir', DI\link('doctrine.hydratorDir'))
        ->method('setHydratorNamespace', DI\link('doctrine.hydratorNamespace'))
        ->method('setMetadataDriverImpl', DI\link('doctrine.annotationDriver'))
        ->method('setDefaultDB', DI\link('db.database')),
];
