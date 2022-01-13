<?php
namespace app\helpers;

use DateTime;
use DateTimeZone;
use Yii;
use yii\db\Command;
use yii\db\Exception;

/**
 * Трейт с функциями обработки данных
 */
trait DataHelpers
{
	/**
	 * @param      $data - данные для обновления(только обновление, создание не тестировалось!!!)
	 * @param      $tableName - имя таблицы для обновления
	 * @param bool $execute - выполнить запрос (true) или просто его создать(false, по умолчанию)
	 *
	 * @return mixed - экземпляр запроса или результат его выполнения
	 */
    public static function batchUpdate($data, $tableName, $execute = false)
    {
        if (!empty($data)) {
            // для максимальной полноты ключей
            $keys = array_keys(current($data));
            foreach ($data as $itm) {
                $keys = array_merge($keys, array_keys($itm));
            }
            $keys = array_unique($keys);
            $last_item = array_pop($keys);
            $update_str = " ON DUPLICATE KEY UPDATE ";
            foreach ($keys as $key) {
                $update_str .= $key." = VALUES(".$key."), ";
            }
            $update_str .= $last_item." = VALUES(".$last_item.")";
            $db = Yii::$app->db;
            $sql = $db->queryBuilder->batchInsert($tableName, array_keys(current($data)), $data);
            $ret = $db->createCommand($sql.$update_str);
            if ($execute) {
                $ret = $ret->execute();
            }
            return $ret;
        } else {
            return false;
        }
    }

	/**
	 * @param      $data - данные для вставки
	 * @param      $tableName - имя таблицы
	 * @param bool $execute - выполнить запрос (true) или просто его создать(false, по умолчанию)
	 *
	 * @return int|Command - экземпляр запроса или результат его выполнения
	 * @throws Exception
	 */
	public static function batchInsert($data, $tableName, $execute = false)
	{
	    if(!empty($data)) {
            $ret = Yii::$app->db->createCommand()
                ->batchInsert($tableName, array_keys(current($data)), $data);
            if ($execute) {
                $ret = $ret->execute();
            }
            return $ret;
        } else {
	        return false;
        }
	}
}