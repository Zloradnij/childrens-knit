<?php

namespace app\modules\catalogHeight\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

abstract class ActiveQueryCatalogAbstract extends \yii\db\ActiveRecord
{
    public const STATUS_ACTIVE = 10;

    public const STATUS_DELETED = 0;

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class'              => BlameableBehavior::class,
                'createdByAttribute' => 'created_user',
                'updatedByAttribute' => 'updated_user',
            ],
        ];
    }

    public function deactivate()
    {
        $this->status = static::STATUS_DELETED;

        return $this->save();
    }

    /**
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function upload() {
        /** @var UploadedFile $file */
        foreach ($this->images as $file) {
            $imageObject = \Yii::createObject(Image::class);

            $image = $imageObject->findByFileObject($file, $this);

            if (!empty($image)) {
                continue;
            }

            $imageObject->object_id = $this->id;
            $imageObject->object_type = static::getObjectType();
            $imageObject->status = Image::STATUS_ACTIVE;
            $imageObject->path = $imageObject->createPath($file);
            $imageObject->title = $file->baseName;


            $file->saveAs(\Yii::getAlias('@webroot') . $imageObject->path);

            $imageObject->save();
        }
    }
}
