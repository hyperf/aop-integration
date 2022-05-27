# Aop Integration

![PHPUnit](https://github.com/hyperf/aop-integration/workflows/PHPUnit/badge.svg)

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


## 配置 AOP 到 Webman 框架

### 安装

- 配置 PSR-4

因为 Hyperf AOP 只能 Hook Composer Autoload，故我们只能切入可以被 Composer 自动加载的类

> 以下省略其他配置

```
  "autoload": {
    "psr-4": {
      "app\\": "app/"
    },
    "files": [
      "./support/helpers.php"
    ]
  },
```

### 增加 AOP 相关配置

我们需要在 `config` 目录下，增加 `config.php` 配置

```php
<?php

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
        // 这里写入对应的 Aspect
        app\aspect\DebugAspect::class,
    ]
];

```

### 配置入口文件 start.php

> 我们将初始化方法，放到 timezone 下方，以下省略其他代码

```
use Hyperf\AopIntegration\ClassLoader;

if ($timezone = config('app.default_timezone')) {
    date_default_timezone_set($timezone);
}

// 初始化
ClassLoader::init();
```

### 测试

首先让我们编写待切入类

```php
<?php
namespace app\service;

class UserService
{
    public function first(): array
    {
        return ['id' => 1];
    }
}
```

其次新增对应的 `DebugAspect`

```php
<?php
namespace app\aspect;

use app\service\UserService;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;

class DebugAspect extends AbstractAspect
{
    public $classes = [
        UserService::class . '::first',
    ];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        var_dump(11);
        return $proceedingJoinPoint->process();
    }
}
```

接下来编辑控制器 `app\controller\Index`

```php
<?php
namespace app\controller;

use app\service\UserService;
use support\Request;

class Index
{
    public function json(Request $request)
    {
        return json(['code' => 0, 'msg' => 'ok', 'data' => (new UserService())->first()]);
    }
}
```

然后配置路由

```php
<?php
use Webman\Route;

Route::any('/json', 'app\controller\Index@json');
```

最后启动服务，并测试。

```shell
php start.php start
curl  http://127.0.0.1:8787/json
```
