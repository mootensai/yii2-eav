<?php

namespace mirocow\eav\models;

use mirocow\eav\models\Eav;
use Yii;

/**
 * This is the model class for table "{{%eav_attribute_value}}".
 *
 * @property integer $id
 * @property integer $entityId
 * @property integer $attributeId
 * @property integer $valueId
 * @property string $value
 * @property integer $optionId
 *
 * @property EavAttribute $attribute
 * @property Eav $entity
 * @property EavAttributeOption $option
 */
class EavAttributeValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eav_attribute_value}}';
    }

    /**
     * Auto increment function for value ID
     *
     * @return int
     */
    public static function getLastValueId($entityId){
        return static::find(['entityId' => $entityId])->max('valueId');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entityId', 'attributeId'], 'required'],
            [['entityId', 'attributeId', 'valueId', 'optionId'], 'integer'],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entityId' => Yii::t('eav', 'Entity ID'),
            'attributeId' => Yii::t('eav', 'Attribute ID'),
            'valueId' => Yii::t('eav', 'Value ID'),
            'value' => Yii::t('eav', 'Value'),
            'optionId' => Yii::t('eav', 'Option ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEavAttribute()
    {
        return $this->hasOne(EavAttribute::className(), ['id' => 'attributeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne(Eav::className(), ['id' => 'entityId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasOne(EavAttributeOption::className(), ['id' => 'optionId']);
    }

    public function getValue()
    {
        return 'XXX';
    }
}