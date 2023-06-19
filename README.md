# 介绍

为 SPA（单页应用程序）、移动应用程序和基于令牌的、简单的 API 提供轻量级身份验证系统。

允许应用程序的每个用户为他们的帐户生成多个 API 令牌。 这些令牌可以被授予指定允许令牌执行哪些操作的权限。

## 工作原理

为了解决两个独立问题而生

### API 令牌

首先，它是一个简单的包，用于向用户发出 API 令牌，而不涉及 `OAuth2`。

这个特性是通过将用户 API 令牌存储在单个数据库表中，并通过包含了有效 API 令牌的 `Authorization` 标识头对传入的请求进行身份验证而实现的。

### SPA 身份验证

其次，提供了一种简单的方法来认证需要与基于 `Laravel` 的 API 进行通信的单页应用程序 (SPAs)。

这些 SPAs 可能与 `Laravel` 应用程序存在于同一仓库中，也可能是一个完全独立的仓库，例如使用 `Vue CLI` 或者 `Next.js` 创建的单页应用。

# 安装方法

您可以通过 `Composer` 软件包管理器安装:

```shell
composer require jundayw/laravel-oauth
```

接下来，你需要使用 `vendor:publish` Artisan 命令发布的配置和迁移文件。

配置文件将会保存在 config 文件夹中：

```shell
php artisan vendor:publish --provider="Jundayw\LaravelOAuth\OAuthServiceProvider"
```

或单独发布配置文件

```shell
php artisan vendor:publish --tag=oauth-config
```

或单独发布迁移文件

```shell
php artisan vendor:publish --tag=oauth-migrations
```

最后，您应该运行数据库迁移。

```shell
php artisan migrate --path=database/migrations/2022_07_23_160710_create_oauth_table.php
```

### 自定义迁移

如果你不想使用默认迁移，你应该在 `App\Providers\AppServiceProvider` 类的 `register` 方法中调用 `OAuth::ignoreMigrations()` 方法。

您可以通过执行以下命令导出默认迁移：

```shell
php artisan vendor:publish --tag=oauth-migrations
```

### 重写 `OAuthToken` 模型

通常，您应该在应用程序的服务提供器的 `boot` 方法中调用此方法：

```php
use App\Models\OAuthToken;
use Jundayw\LaravelOAuth\OAuth;

/**
 * 引导应用程序服务。
 *
 * @return void
 */
public function boot()
{
    OAuth::oAuthTokenModelUsing(OAuthToken::class);
}
```

### 重写 `RefreshToken` 模型

通常，您应该在应用程序的服务提供器的 `boot` 方法中调用此方法：

```php
use App\Models\RefreshToken;
use Jundayw\LaravelOAuth\OAuth;

/**
 * 引导应用程序服务。
 *
 * @return void
 */
public function boot()
{
    OAuth::refreshTokenModelUsing(RefreshToken::class);
}
```

# 配置

配置文件 `config/oauth.php`

```php
return [
    // 加密秘钥
    'secret' => env('OAUTH_SECRET', env('APP_KEY')),

    // 加密方法
    'hash' => 'sha256',
    
    // 数据库存储令牌表
    'table' => 'oauth',
    
    // 访问令牌过期时间
    'access_token_expire_in' => 2 * 3600,
    
    // 刷新令牌过期时间
    'refresh_token_expire_in' => 24 * 3600 * 15,
    
    // 多[客户端/设备]是否同时在线，默认：开启
    // 如：同一账户是否允许[手机/电脑]同时在线
    'multiple_devices' => true,
    
    // 相同[客户端/设备]是否同时在线，默认：开启
    // 如：同一账户是否允许电脑端同时在线
    'concurrent_device' => true,
];
```

# 授权看守器

配置文件 `config/auth.php` 中， 将授权看守器 `guards` 的 `driver` 参数的值设置为 `oauth`。

```php
return [
    // ...
    'guards' => [
        // 如果只有一个 oauth 看守器 provider 可为 null
        'client' => [
            'driver' => 'oauth',
            'provider' => null,
        ],
        // 如果多个 oauth 看守器 provider 需要配置
        'manager' => [
            'driver' => 'oauth',
            'provider' => 'managers',
        ],
    
        'user' => [
            'driver' => 'oauth',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'managers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Manager::class,
        ],
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],
    // ...
];
```

# 解决冲突

`laravel` 框架默认内置了（`Sanctum` 在处理 `APP` 开发时，无法实现令牌刷新功能）此时使用 `OAuth` 将造成冲突，解决方案：

使用 `HasAccessTokens` 功能替代 `HasApiTokens`，如果你其他模块使用到了 `Sanctum`

```php
use Jundayw\LaravelOAuth\Contracts\HasAccessTokensContract;
use Jundayw\LaravelOAuth\HasAccessTokens;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements HasAccessTokensContract
{
    use HasApiTokens, HasAccessTokens {
        HasAccessTokens::currentAccessToken insteadof HasApiTokens;
        HasAccessTokens::withAccessToken insteadof HasApiTokens;
        HasAccessTokens::createToken insteadof HasApiTokens;
        HasAccessTokens::tokens insteadof HasApiTokens;
        HasAccessTokens::tokenCan insteadof HasApiTokens;
    }
}
```

也可以将 `HasApiTokens` 删除

```php
use Jundayw\LaravelOAuth\Contracts\HasAccessTokensContract;
use Jundayw\LaravelOAuth\HasAccessTokens;

class User extends Authenticatable implements HasAccessTokensContract
{
    use HasAccessTokens;
}
```

# 发布 API Tokens

允许你发布 API 令牌，用于对你的应用程序的 API 请求进行身份验证。 使用 API 令牌发出请求时，令牌应作为 `Bearer` 令牌包含在 `Authorization` 请求头中。

```php
use Jundayw\LaravelOAuth\Contracts\HasAccessTokensContract;
use Jundayw\LaravelOAuth\HasAccessTokens;

class User extends Authenticatable implements HasAccessTokensContract
{
    use HasAccessTokens;
}
```

要发布令牌，你可以使用 createToken 方法。 createToken 方法返回一个 `Jundayw\LaravelOAuth\Token` 实例。

```php
use Illuminate\Http\Request;

Route::post('/tokens/create', function(Request $request) {
    return $request->user()
        ->createToken($request->token_name, $request->device_name, ['check-status', 'place-orders'])
        ->toArray();
});
```

你可以使用 `HasAccessTokens` trait 提供的 `tokens` Eloquent 关系访问用户的所有令牌：

```php
foreach($user->tokens as $token) {
    //
}
```

# 刷新 API Tokens

```php
use Illuminate\Http\Request;
use Jundayw\LaravelOAuth\RefreshToken;

Route::post('/tokens/refresh', function(Request $request, RefreshToken $refreshToken) {
    return $refreshToken->refreshToken($refreshToken->findTokenByRequest($request))->toArray();
});
```

# 令牌作用域

`OAuth` 内置 `scopes` 及 `scope` 中间件：

`scopes` 中间件可以分配给一个路由，以验证传入请求的令牌是否具有所有列出的能力：

```php
Route::get('/orders', function() {
    // Token has both "check-status" and "place-orders" abilities...
})->middleware(['auth:client', 'scopes:check-status,place-orders']);
```

`scope` 中间件可以分配给一个路由，以验证传入请求的令牌是否具有至少一个列出的能力：

```php
Route::get('/orders', function() {
    // Token has the "check-status" or "place-orders" ability...
})->middleware(['auth:client', 'scope:check-status,place-orders']);
```

# 保护路由

```php
use Illuminate\Http\Request;

Route::middleware(['api', 'auth:client'])->get('/user', function(Request $request) {
    return $request->user();
});
```

# 最佳实践

第一步：配置文件 `config/auth.php`：

```php
return [
    // ...
    'guards' => [
        'manager' => [
            'driver' => 'oauth',
            'provider' => 'managers',
        ],
    
        'user' => [
            'driver' => 'oauth',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'managers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Manager::class,
        ],
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],
    // ...
];
```

第二步：实现接口

```php
use Jundayw\LaravelOAuth\Contracts\HasAccessTokensContract;
use Jundayw\LaravelOAuth\HasAccessTokens;

class User extends Authenticatable implements HasAccessTokensContract
{
    use HasAccessTokens;
}
```

```php
use Jundayw\LaravelOAuth\Contracts\HasAccessTokensContract;
use Jundayw\LaravelOAuth\HasAccessTokens;

class Manager extends Authenticatable implements HasAccessTokensContract
{
    use HasAccessTokens;
}
```

第三步：配置文件 `routes/web.php`：

```php
use App\Models\User;
use App\Models\Manager;
use Illuminate\Http\Request;
use Jundayw\LaravelOAuth\RefreshToken;

// 发布 user 令牌
Route::get('/user', function(User $user) {
    return $user->first()?->createToken('测试-user', 'APP')->toArray();
});

// 发布 manager 令牌，有作用域
Route::get('/manager', function(Manager $manager) {
    return $manager->first()
        ?->createToken('测试-manager', 'PC', ['snsapi_base', 'snsapi_userinfo'])
        ->toArray();
});

// 获取 user 当前账户
Route::middleware(['auth:user'])->post('/user-info', function(Request $request) {
    return $request->user();
});

// 获取 manager 当前账户，验证作用域
Route::middleware(['auth:manager', ['scope:snsapi_userinfo']])
    ->post('/manager-info', function(Request $request) {
        return $request->user();
    });

// 刷新当前账户
Route::get('/refresh', function(\Illuminate\Http\Request $request, \Jundayw\LaravelOAuth\RefreshToken $refreshToken) {
    return $refreshToken->refreshToken($refreshToken->findTokenByRefreshToken($request->bearerToken()))->toArray();
});
```
