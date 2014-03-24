Questions and Answers for Yii 2
=======

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
	'modules' => [
		'qa' => 'artkost\qa\Module',
		'userClass' => 'app\models\User',
		'userNameFormatter' => 'getUserName'
		...
	],
	...
];
```

Install Migrations

```php yii migrate --migrationPath=@artkost/qa/migrations```

You can then access QA through the following URL:

```
http://localhost/path/to/index.php?r=qa
```
