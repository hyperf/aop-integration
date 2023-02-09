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
namespace Hyperf\AopIntegration;

use Hyperf\AopIntegration\Provider\ContainerProvider;
use Hyperf\Di\Aop\AstVisitorRegistry;
use Hyperf\Di\Aop\ProxyCallVisitor;
use Hyperf\Di\ClassLoader as Loader;
use Hyperf\Di\ScanHandler\ScanHandlerInterface;
use Hyperf\Pimple\ContainerFactory;

class ClassLoader extends Loader
{
    public static function init(?string $proxyFileDirPath = null, ?string $configDir = null, ?ScanHandlerInterface $handler = null): void
    {
        if (! AstVisitorRegistry::exists(ProxyCallVisitor::class)) {
            AstVisitorRegistry::insert(ProxyCallVisitor::class, PHP_INT_MAX / 2);
        }

        (new ContainerFactory([
            ContainerProvider::class,
        ]))();

        parent::init($proxyFileDirPath, $configDir, $handler);
    }

    protected static function loadDotenv(): void
    {
    }
}
