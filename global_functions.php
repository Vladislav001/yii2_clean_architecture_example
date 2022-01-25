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