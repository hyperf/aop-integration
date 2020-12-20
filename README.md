# Aop Integration

## 安装

```
composer require hyperf/aop-integration
```

## 配置 AOP 到 ThinkPHP 框架

1. 添加配置到 `config/config.php` 中

```php
<?php

declare(strict_types=1);

! defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));

return [
    'annotations' => [
        'scan' => [
            'paths' => [
                BASE_PATH . '/app',
            ],
            'ignore_annotations' => [
                'mixin',
            ],
            'class_map' => [
            ],
        ],
    ],
    'aspects' => [
        // 在此配置可用的 Aspect
    ],
];

```

2. 修改入口文件

```php
<?php

declare(strict_types=1);

namespace think;

use Hyperf\AopIntegration\ClassLoader;

require __DIR__ . '/../vendor/autoload.php';

// 初始化 AOP
! defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));

ClassLoader::init();

// 省略其他代码

```
