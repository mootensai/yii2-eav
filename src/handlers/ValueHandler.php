<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace mirocow\eav\handlers;

use mirocow\eav\widgets\AttributeHandler;
use yii\db\ActiveRecord;

/**
 * Class ValueHandler
 * @package mirocow\eav
 *
 * @property ActiveRecord $valueModel
 * @property string $textValue
 */
abstract class ValueHandler
{
        const STORE_TYPE_RAW = 0;
        const STORE_TYPE_OPTION = 1;
        const STORE_TYPE_MULTIPLE_OPTIONS = 2;
        const STORE_TYPE_ARRAY = 3; // Json encoded

        /** @var AttributeHandler */
        public $attributeHandler;

        /**
         * @return ActiveRecord
         * @throws \Exception
         * @throws \yii\base\InvalidConfigException
         */
        public function getValueModel()
        {
                $EavModel = $this->attributeHandler->owner;

                /** @var ActiveRecord $valueClass */
                $valueClass = $EavModel->valueClass;
                $entityId = $EavModel->entityModel->getPrimaryKey();
                $attributeId = $this->attributeHandler->attributeModel->getPrimaryKey();
                $valueId = $EavModel->entityModel->valueId;

                if($EavModel->entityModel->isUpdate){
                    $valueModel = $valueClass::findOne([
                        'entityId' => $entityId,
                        'attributeId' => $attributeId,
                        'valueId' => $valueId
                    ]);
                }else{
                    $valueModel = new $valueClass;
                    $valueModel->entityId = $entityId;
                    $valueModel->attributeId = $attributeId;
                    $valueModel->valueId = $valueId;
                }
                
                if (!$valueModel instanceof ActiveRecord) {
                        /** @var ActiveRecord $valueModel */
                        $valueModel = new $valueClass;
                        $valueModel->entityId = $entityId;
                        $valueModel->attributeId = $attributeId;
                }

                return $valueModel;
        }

        abstract public function defaultValue();

        abstract public function load();

        abstract public function save();

        abstract public function getTextValue();

        // TODO 7: Add rules from $attributeModel->getEavOptions()
        abstract public function addRules();
}