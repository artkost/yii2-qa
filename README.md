Questions and Answers for Yii 2
=======

[![Packagist Version](https://img.shields.io/packagist/v/artkost/yii2-qa.svg?style=flat-square)](https://packagist.org/packages/artkost/yii2-qa)
[![Total Downloads](https://img.shields.io/packagist/dt/artkost/yii2-qa.svg?style=flat-square)](https://packagist.org/packages/artkost/yii2-qa)

Extension provides web QA inspired by Stack Overflow.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist artkost/yii2-qa "*"
```

or add

```
"artkost/yii2-qa": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply modify your application configuration as follows:

```php
return [
	'qa' => [
		'class' => 'artkost\qa\Module',
		'userNameFormatter' => 'getUserName'
	],
	...
];
```

Install Migrations

```php yii migrate/up --migrationPath=@vendor/artkost/yii2-qa/migrations```

You can then access QA through the following URL:

```
http://localhost/path/to/index.php?r=qa
```

Widgets
-----

You can use available widgets
```php
<?= Tags::widget(['limit' => 20]) ?>
<?= Popular::widget(['limit' => 20, 'views' => 20]) ?>
<?= Favorite::widget(['userID' => Yii::$app->user->id, 'limit' => 20]) ?>
```
