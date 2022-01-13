<?php

define('PATH_APPLICATION_DEPENDENCIES', $_SERVER['DOCUMENT_ROOT'] . '/Application/dependencies.php');
define("ACCESS_TOKEN_LIFE_TIME", 604800); // 3 дня
define("REFRESH_TOKEN_LIFE_TIME", 2592000); // 30 дней
define("PASSWORD_RESET_TOKEN_LIFE_TIME", 3600); // 1 час

define("PATH_WEB", '/web/');
define("PATH_UPLOADS", 'uploads/');

define('DICTIONARY', array(
	'DIRECTION_ARCHIVE' => "Архив"
));

// МБ ИЗМЕНЯЕМЫЕ ПАРАМЕТРЫ ПОСЛЕ ПЕРЕНОСА НА PROD //
define("FRONTEND_URL", 'https://ren-dashboard.ru/');
define("DOMAIN_EMAIL", 'v.guriev@lince.studio');