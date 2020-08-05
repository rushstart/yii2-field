<?php

namespace rushstart\field\models;



use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Instance]].
 *
 * @see Instance
 */
class InstanceQuery extends ActiveQuery
{
    /**
     * @return InstanceQuery
     */
    public function active()
    {
        return $this->andWhere(['status' => Instance::STATUS_ACTIVE]);
    }
}
