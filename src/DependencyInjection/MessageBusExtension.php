<?php

namespace SerpSamuraiIo\MessageBus\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MessageBusExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        var_dump('We\'re alive!');die;
    }
}
