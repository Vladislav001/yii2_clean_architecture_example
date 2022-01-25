<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

class UploadProjectLogoForm extends Model
{
	/**
	 * @var UploadProjectLogoForm
	 */
	public $file;
	public $projectId;

	public function rules()
	{
		return [
			[
				['file'],
				'file',
				'skipOnEmpty' => true,
				'extensions' => 'png, jpg, jpeg',
				'maxSize' => 5 * (1024 * 1024), //5MB
				'maxFiles' => 1
			]
		];
	}

	public function upload()
	{
		if ($this->validate())
		{
			$filePath = null;
			$dirPath = PATH_UPLOADS . $this->projectId . '/logo/';

			FileHelper::createDirectory($dirPath);

			if ($this->file)
			{
				$fileName = '1';
				$filePath = $dirPath . $fileName . '.' . $this->file->extension;
				$this->file->saveAs($filePath);
			}

			return $filePath;
		} else
		{
			throw new \Exception(current($this->errors)[0]);
		}
	}

	public function deleteAllFiles()
	{
		$dirPath = PATH_UPLOADS . $this->projectId . '/logo/';
		$files = glob($dirPath . '*');
		foreach ($files as $file)
		{
			if (is_file($file))
			{
				unlink($file);
			}
		}
	}
}