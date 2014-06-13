tachcard_tasks
==============

##INSTALL
> npm -g install bower
> bower install
> composer install

> В файле public/index.php редактируем параметры подключения к БД
БД должна быть создана заранее

```php
$app->configureMode('development', function () use ($app) {
    $app->config([
        'log.enable' => true,
        'debug' => true,
        'templates.path' => '../application/views',

        'db.host' => 'localhost',
        'db.port' => 3306,
        'db.user' => 'root',
        'db.password' => 'bitnami',
        'db.name' => 'com.tachcard.jasper.app'
    ]);
});
```

> Перейдите по сслыке http://$ваш домен$/install