yii-redis-session-manager
=========================

Redis Session Manager for Yii Framework

## Requirements

 * Redis server from http://redis.io/
 * A PHP extension for Redis https://github.com/nicolasff/phpredis

## Install

Copy RedisSessionManager.php to application/components/ and set in config:

```php
'import' => array(
    'application.components.*',
),
'components' => array(
    'session' => array(
        'class' => 'application.components.RedisSessionManager',
        'autoStart' => true,
        'cookieMode'=>'none', //set php.ini to session.use_cookies = 0, session.use_only_cookies = 0
        'useTransparentSessionID' => true, //set php.ini to session.use_trans_sid = 1
        'sessionName' => 'session',
        'saveHandler'=>'redis',
        'savePath' => 'tcp://localhost:6379?database=10&prefix=session::',
        'timeout' => 28800, //8h
    ),
),
```





