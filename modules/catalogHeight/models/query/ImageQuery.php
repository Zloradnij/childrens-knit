<?php

namespace app\modules\catalogHeight\models\query;

use app\modules\catalogHeight\models\ActiveQueryCatalogAbstract;
use yii\web\UploadedFile;

/**
 * This is the ActiveQuery class for [[\app\modules\catalogHeight\models\Image]].
 *
 * @see \app\modules\catalogHeight\models\Image
 */
class ImageQuery extends \yii\db\ActiveQuery
{
    public function findByFileObject(UploadedFile $file, ActiveQueryCatalogAbstract $object)
    {
        $path = $this->createPath($file);

        if (!file_exists(\Yii::getAlias('@webroot') . $this->path)) {
            mkdir(\Yii::getAlias('@webroot') . $this->path, 0755, true);
        }

        $path .= md5($file->baseName) . '.' . $file->extension;

        if (file_exists(\Yii::getAlias('@webroot') . $this->path)) {
            return;
        }

        return $this->andWhere([
            'object_id'   => $object->id,
            'object_type' => $object::getObjectType(),
        ]);
    }
}
