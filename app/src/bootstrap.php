<?php

$container = \App\Base\Container::getInstance();
$container->set(\App\Factory\ViewFactory::class, function (){
    return new \App\Factory\ViewFactory();
});
