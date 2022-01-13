<?php
use FrameworksDrivers\ErrorService;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'TVX3ZKGZ9rkeAIzhSLDhaZTRb1LAylsA',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
			'enableSession' => false
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
		'authManager' => [
			'class' => 'yii\rbac\DbManager',
		],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
				'<action>' => 'site/<action>',

			],
		],
		'response' => [
			'class' => 'yii\web\Response',
			'format' => 'json',
			'on beforeSend' => function ($event)
			{
				$response = $event->sender;

				if ($response->data !== null)
				{
					$data = $response->data;

					// Error handle
					$errors = array();
					if (!$response->isSuccessful)
					{
						// logToFile($data, 'errors.log');

						if (isset($data['message']))
						{
							$errorService = new ErrorService();

							if ($data['type'] === 'yii\db\Exception')
							{
								// проверка БД исключений
								$errorService->addError('database_error');
								$errors = $errorService->getErrors();
							} else if ($data['type'] == 'Exception' && $errorService->ERROR_DICTIONARY[$data['message']])
							{
								// проверка исключений, попадающих под свои правила
								$errorService->addError($data['message']);
								$errors = $errorService->getErrors();
							} else
							{
								// другие исключения Yii2
								$errors[] = array(
									"id" => "some_error",
									"title" => $data['message']
								);
							}
						} elseif (isset(current($data)['message']))
						{
							$errors[] = array("id" => "some_error", "title" => current($data)['message']);
						}
					}

					$response->data = [];

					if ($errors)
					{
						$response->data['errors'] = $errors;
					} else if ($response->isSuccessful)
					{
						$response->data['result'] = $data;
						$response->statusCode = 200;
					}
				}
			},
		]
    ],
    'params' => $params,

//	'as beforeRequest' => [
//		'class'        => yii\filters\AccessControl::class,
//		'rules'        => [
//			[
//				'allow'       => true,
//				'actions'     => ['get-profile'],
//				'roles'       => ['@'],
//			],
//		],
//		'denyCallback' => function ()
//		{
//			//print_r(\Yii::$app->user);
//			die('доступ запрещен');
//		},
//	],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
		//'allowedIPs' => [''],
    ];
}

return $config;
