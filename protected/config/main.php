<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Internet System for Ordering Physics',
	'timeZone' => 'Europe/Warsaw',
	'language' => 'pl',
	// preloading 'log' component
	'preload'=>array('log','UnderConstruction'),
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		//uncomment the following to enable the Gii tool
		/*
		 'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'your_password', //change this to your password
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		 */
		),

	// application components
	'components'=>array(
		'user'=>array(
					'class'=>'WebUser',
					'allowAutoLogin'=>true,
					'loginUrl'=>array('/site/login'),
		),
		'request'=>array(
				'enableCsrfValidation'=>true,
			  //'enableCookieValidation'=>true
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'caseSensitive'=>true,
			'showScriptName'=>false,
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'~<view:\w+'=>'site/page'
			),
		),
		'session'=>array(
            'class' => 'CDbHttpSession',
            'connectionID' => 'db',
            'sessionTableName' => 'dbsession',
        ),
        
		'db'=>array(
			'connectionString' => 'mysql:host=hostname;dbname=database', // change 'hostname' to your hostname and 'database' to your database name
			'emulatePrepare' => true,
			'username' => 'dbusername', // your database username
			'password' => 'dbpassword', //your database user password
			'charset' => 'utf8',
		),
		'UnderConstruction' => array(
				'class' => 'application.components.UnderConstruction',
				//'allowedIPs'=>array('127.0.0.1'), //whatever IPs you want to allow
				'locked'=>false, //this is the on off switch
				//'redirectURL'=>'',//put in your desired
				),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        'clientScript' => array(
        		'scriptMap' => array(
        				'jquery-ui.css' => '/css/ui/jquery-ui.css',
        		),
        ),
		'log'=>array(
			'class' => 'CLogRouter',
                        'routes' => array(
                                array(
                                        'class' => 'CDbLogRoute',
                                        'logTableName' => 'yii_log',
                                        'connectionID' => 'db',
                                        'levels' => 'error, warning',
                                ),
                                array(
                                        'class' => 'AdvancedEmailLogRoute',
                                        'filter' => array(
                                                'class'=>'AdvancedLogFilter',
                                                'ignoreCategories' => array(
                                                        // Ignore 404s
                                                        'exception.CHttpException.404',
                                                ),
                                        ),
                                        'levels' => 'error',
                                        'emails' => 'errors[at]your.email.com', //change this to your email address where you want to receive error messages
                                ),
                        ),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'marcin.wionczyk@pwr.wroc.pl',
		//ldap settings
		'ldap' => array(
					'host' => 'ldap hostname', //change this to your ldap hostname
					'dc' => 'dc string', //change this to your dc attribute
			),
	),
);
