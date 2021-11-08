<?php

namespace mirocow\eav\models;

use Yii;

/**
 * This is the model class for table "{{%eav_entity}}".
 *
 * @property integer $id
 * @property string $entityName
 * @property string $entityModel
 *
 * @property EavAttribute[] $eavAttributes
 */
class EavEntity extends \yii\db\ActiveRecord
{
    public bool $isUpdate = true;
    public int $valueId;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eav_entity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entityModel', 'entityName'], 'string', 'max' => 100],
            [['entityModel', 'entityName'], 'required'],
            [['categoryId'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entityName' => Yii::t('eav', 'Entity name'),
            'entityModel' => Yii::t('eav', 'Entity Model'),
            'categoryId' => Yii::t('eav', 'ID Category'),
            'modelId' => Yii::t('eav', 'ID Model')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEavAttributes()
    {
        return $this->hasMany(EavAttribute::className(), ['entityId' => 'id'])
            ->orderBy(['order' => SORT_DESC]);
    }

    public function viewModelAttributes(){
        foreach($this->getEavAttributes()->all() as $key => $attr){
            $attributes[] = [
                'label' => $attr->label,
                'value' => $this::getValue($this, $attr, $key)
            ];
        }

        return $attributes;
    }

    public static function getValue(EavEntity $model, EavAttribute $attr, int $key){
        $value = $model[$attr->name];
        if($attr->typeId == 2){
            $value = $attr->eavAttributeValues[$key]->option->value;
            return $value;
        }
        if($value instanceof \mirocow\eav\EavModel){
            $value = $model[$attr->name]->value;
            return $value;
        }
        return $value;
    }

    public static function getModelAttributes(EavEntity $model){
        foreach ($model->getEavAttributes()->all() as $key => $attr) {
            $attributes[$attr->label] = $model::getValue($model, $attr, $key);
        }
        
        return $attributes;
    }

    public static function getValueList($entityId){
        $modelList = [];
        $ids = EavAttributeValue::find()->select(['valueId'])->where(['entityId' => $entityId])->distinct()->all();
        
        foreach ($ids as $id) {
            $model = static::findOne(['entityModel' => self::className()]);
            $model->valueId = $id->valueId;
            $modelList[] = static::getModelAttributes($model);
        }

        return $modelList;
    }
}