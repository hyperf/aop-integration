<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Hyperf\AopIntegration\Provider;

use Hyperf\Contract\ContainerInterface;
use Hyperf\Pimple\ProviderInterface;
use Psr;

class ContainerProvider implements ProviderInterface
{
    public function register(ContainerInterface $container)
    {
        $container->set(ContainerInterface::class, $container);
        $container->set(Psr\Container\ContainerInterface::class, $container);
    }
}
