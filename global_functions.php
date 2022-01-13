<?php

function logToFile($data, $file = '__yii_log.log', $isRemove = false)
{
	$path = __DIR__ . '/log/' . $file;
	// Удалить файл если есть условие на удаление
	if ($isRemove && is_file($path)) unlink($path);

	$tempFile = fopen(__DIR__ . '/log/' . $file, 'a');
	fwrite($tempFile, __FILE__ . ':' . __LINE__ . PHP_EOL . '(' . date('Y-m-d H:i:s') . ')' . PHP_EOL . print_r($data, TRUE) . PHP_EOL . PHP_EOL);
	fclose($tempFile);
}

/**
 * @see https://gist.github.com/kovaldn/6166105
 * @param $str
 * @return string|string[]|null
 */
function createTransliteration($str) {

	$rus = array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',' ');

	$lat = array('a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya','a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya',' ');

	$str = str_replace($rus, $lat, $str); // перевеодим на английский
	$str = str_replace('-', '', $str); // удаляем все исходные "-"
	$transliteration = preg_replace('/[^A-Za-z0-9-]+/', '-', $str); // заменяет все символы и пробелы на "-"
	return $transliteration;
}