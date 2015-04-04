# yii2-lightncandy
Yii2 lightncandy extention/

This extension provides a `ViewRender` that would allow you to use [LightnCandy](https://github.com/zordius/lightncandy), an extremely fast PHP implementation of handlebars and mustache.

To use this extension, simply add the following code in your application configuration:

```php
return [
    //....
    'components' => [
        'view' => [
            'renderers' => [
                'handlebars' => [
                    'class' => 'kfreiman\lightncandy\ViewRenderer',
                    // the file extension of Handlebars templates
                    // 'extension' => ['.handlebars', '.mustache']',
                    // path alias pointing to where Handlebars cache will be stored. Set to false to disable templates cache.
                    // 'cache_preffix' => 'LightnCandy_',
                    // 'flags' => LightnCandy::FLAG_INSTANCE |
                    //    LightnCandy::FLAG_NOESCAPE |
                    //    LightnCandy::FLAG_SPVARS |
                    //    LightnCandy::FLAG_RUNTIMEPARTIAL |
                    //    LightnCandy::FLAG_HANDLEBARSJS
                    //
                    //    and at Yii dev enviroment
                    //    LightnCandy::FLAG_ERROR_EXCEPTION |
                    //    LightnCandy::FLAG_RENDER_DEBUG
                    //  additional LightnCandy options
                    // 'options' => []',
                ],
            ],
        ],
    ],
];
```
Or, you can declarate view component in your code
```php
Yii::$app->set('view', [
    'class' => 'yii\web\View',
    'renderers' => [
        'handlebars' => [
            'class' => 'kfreiman\lightncandy\ViewRenderer',
            'extension' => ['.handlebars','.js','.mustache'],
        ],
    ]
]);
```

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist kfreiman/yii2-lightncandy
```

or add

```
"kfreiman/yii2-lightncandy": "*"
```

to the require section of your composer.json.
