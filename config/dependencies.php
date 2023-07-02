<?php

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions([
    PDO::class => \Produtos\Action\Infrastructure\Persistence\ConnectionCreator::createConnection(),
]);

/** @var \Psr\Container\ContainerInterface $container */
$container = $builder->build();

return $container;
