<?php

namespace lantra\sp\migrations;

use Craft;
use craft\db\Migration;
use craft\models\EntryType;
use craft\elements\Entry;
use verbb\supertable\services\SuperTableService;

use lantra\sp\helpers\MigrationHelper;

/**
 * m200120_163920_update_modules migration.
 */
class m200120_163920_update_modules extends Migration
{

    private $_fieldIds;

    /**
     * @return bool|void
     * @throws \Throwable
     * @throws \craft\errors\EntryTypeNotFoundException
     */
    public function safeUp()
    {
        $this->_updateModuleType();
        $this->_updateModuleResult();
        $this->_updateCpdType();
        $this->_updateUnitGroup();
        $this->_updateModule();
        $this->_addUnitHeading();
        $this->_fixModulesSection();
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\SectionNotFoundException
     */
    private function _fixModulesSection()
    {
        $sectionsService = Craft::$app->getSections();
        if(false != $modules = $sectionsService->getSectionByHandle('modules')) {
            $modules->type = 'structure';
            $modules->structureId = null;
            $sectionsService->saveSection($modules);
        }
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\SectionNotFoundException
     */
    private function _updateModuleResult()
    {
        $resultLocked = MigrationHelper::createField(
            'craft\fields\Lightswitch',
            'Result Locked',
            'resultLocked',
            6,
            ['default' => '']
        );

        $cycleName = MigrationHelper::createField(
            'craft\fields\PlainText',
            'Cycle Name',
            'cycleName',
            6
        );

        $cycleStartDate = MigrationHelper::createField(
            'craft\fields\Date',
            'Cycle Start Date',
            'cycleStartDate',
            6
        );

        $cycleFinishDate = MigrationHelper::createField(
            'craft\fields\Date',
            'Cycle Finish Date',
            'cycleFinishDate',
            6
        );

        $resultHours = MigrationHelper::createField (
            'craft\fields\Number',
            'Result Hours',
            'resultHours',
            4,
            [
                'defaultValue' => 1,
                'min' => 1,
                'max' => ''
            ]
        );

        $resultValue = MigrationHelper::createField (
            'craft\fields\Number',
            'Result Value',
            'resultValue',
            4,
            [
                'defaultValue' => 1,
                'min' => 1,
                'max' => ''
            ]
        );

        $fieldsService = Craft::$app->getFields();

        $entryType = MigrationHelper::getEntryTypeByHandle('moduleResult');
        $fieldLayoutArray = MigrationHelper::getFieldLayoutArray($entryType);
        $fieldLayoutArray['Result'] = [
            $this->_fieldId('resultModule'),
            $this->_fieldId('resultStatus'),
            (int)$resultLocked->id,
            (int)$resultHours->id,
            (int)$resultValue->id
        ];
        $fieldLayoutArray['Cycle'] = [$cycleName->id, $cycleStartDate->id, $cycleFinishDate->id];
        $fieldLayout = $fieldsService->assembleLayout($fieldLayoutArray);
        $fieldLayout->id = $entryType->getFieldLayoutId();
        $fieldLayout->type = 'craft\elements\Entry';
        $fieldsService->saveLayout($fieldLayout);
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\SectionNotFoundException
     */
    private function _addUnitHeading()
    {
        $unitTooltip = MigrationHelper::createField(
            'craft\fields\PlainText',
            'Unit Tooltip',
            'unitTooltip',
            4,
            ['multiline' => true]
        );

        $fieldsService = Craft::$app->getFields();

        $entryType = MigrationHelper::getEntryTypeByHandle('unit');
        $fieldLayoutArray = MigrationHelper::getFieldLayoutArray($entryType);
        $fieldLayoutArray['Unit'] = [
            $this->_fieldId('unitType'),
            (int)$unitTooltip->id,
            $this->_fieldId('unitUrl'),
            $this->_fieldId('unitValue'),
            $this->_fieldId('unitEndorsementManagerLevel'),
            $this->_fieldId('unitDescription'),
            $this->_fieldId('unitImage'),
            $this->_fieldId('unitFiles'),
            $this->_fieldId('unitHeading')
        ];
        $fieldLayout = $fieldsService->assembleLayout($fieldLayoutArray);
        $fieldLayout->id = $entryType->getFieldLayoutId();
        $fieldLayout->type = 'craft\elements\Entry';
        $fieldsService->saveLayout($fieldLayout);
    }

    /**
     * @param $fieldHandle
     * @return null
     */
    private function _fieldId($fieldHandle)
    {
        if (is_null($this->_fieldIds)) {
            $fieldsService = Craft::$app->getFields();
            foreach ($fieldsService->getAllFields(false) as $field) {
                $this->_fieldIds[$field->handle] = $field->id;
            }
        }
        return isset($this->_fieldIds[$fieldHandle]) ? $this->_fieldIds[$fieldHandle] : null;
    }

    /**
     * @param $handle
     * @return \verbb\supertable\models\SuperTableBlockTypeModel|void
     */
    private function _getBlockTypeByFieldHandle($handle)
    {
        $fieldsService = Craft::$app->getFields();
        if (false == $field = $fieldsService->getFieldByHandle($handle)) {
            return;
        }
        $supertableService = new SuperTableService();
        $blockTypes = $supertableService->getAllBlockTypes();
        foreach($blockTypes as $blockType) {
            if ($blockType->fieldId == $field->id) {
                return $blockType;
            }
        }
        return;
    }

    /**
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     */
    private function _updateModule()
    {
        ## add required field to column layout
        $supertableService = new SuperTableService();
        $fieldsService = Craft::$app->getFields();
        if (false == $columnLayout = $this->_getBlockTypeByFieldHandle('columnLayout')) {
            return;
        }

        $fieldRequired = MigrationHelper::createField(
            'craft\fields\Lightswitch',
            'Field Required',
            'fieldRequired',
            null,
            ['default' => ''],
            '',
            'superTableBlockType:'.$columnLayout->uid
        );

        $fieldLayoutArray = MigrationHelper::getFieldLayoutArray($columnLayout);
        $fieldLayoutArray['Content'] = [
            $this->_fieldId('fieldType'),
            $this->_fieldId('fieldLabel'),
            $this->_fieldId('fieldCustomKey'),
            $this->_fieldId('fieldCustomType'),
            $this->_fieldId('fieldCustomOptions'),
            $fieldRequired->id,
            $this->_fieldId('fieldManagerOnly')
        ];
        $fieldLayout = $fieldsService->assembleLayout($fieldLayoutArray);
        $fieldLayout->id = $columnLayout->getFieldLayout()->id;
        $fieldLayout->type = 'verbb\supertable\elements\SuperTableBlockElement';
        $fieldsService->saveLayout($fieldLayout);
        $supertableService->saveBlockType($columnLayout);

        ## add column layout to cpd module type
        $cpdEntryType = MigrationHelper::getEntryTypeByHandle('cpd');
        $fieldLayoutArray = MigrationHelper::getFieldLayoutArray($cpdEntryType);
        $fieldLayoutArray['Column Layout'] = [$columnLayout->fieldId];

        $fieldLayout = $fieldsService->assembleLayout($fieldLayoutArray);
        $fieldLayout->id = $cpdEntryType->getFieldLayout()->id;
        $fieldLayout->type = Entry::class;
        $fieldsService->saveLayout($fieldLayout);

        ## add column layout to qualification module type
        $qualificationEntryType = MigrationHelper::getEntryTypeByHandle('qualification');
        $fieldLayoutArray = MigrationHelper::getFieldLayoutArray($qualificationEntryType);
        $fieldLayoutArray['Column Layout'] = [$columnLayout->fieldId];

        $fieldLayout = $fieldsService->assembleLayout($fieldLayoutArray);
        $fieldLayout->id = $qualificationEntryType->getFieldLayout()->id;
        $fieldLayout->type = Entry::class;
        $fieldsService->saveLayout($fieldLayout);
    }

    /**
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     */
    private function _updateUnitGroup()
    {
        $enableSubmissions = MigrationHelper::createField(
            'craft\fields\Lightswitch',
            'Enable Multiple Result Submissions?',
            'enableSubmissions',
            4,
            ['default' => ''],
            'Enable multiple submissions for units in this group?'
        );

        $unitGroupUnitValue = MigrationHelper::createField(
            'craft\fields\Number',
            'Unit Group Unit Value',
            'unitGroupUnitValue',
            4,
            ['min' => 0],
            'Override unit value for unit group (leave blank for default unit values)'
        );

        $matrixService = Craft::$app->getMatrix();
        $fieldsService = Craft::$app->getFields();

        $unitGroup = $matrixService->getBlockTypeById(1);
        $fieldLayoutArray = MigrationHelper::getFieldLayoutArray($unitGroup);
        $fieldLayoutArray['Content'] = [
            $this->_fieldId('groupName'),
            $this->_fieldId('unitEntries'),
            (int) $enableSubmissions->id,
            (int) $unitGroupUnitValue->id
        ];
        $fieldLayout = $fieldsService->assembleLayout($fieldLayoutArray);
        $fieldLayout->id = $unitGroup->getFieldLayout()->id;
        $fieldLayout->type = 'craft\elements\MatrixBlock';
        $fieldsService->saveLayout($fieldLayout);
        $matrixService->saveBlockType($unitGroup);
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\EntryTypeNotFoundException
     */
    private function _updateModuleType()
    {
        $entryType = Craft::$app->getSections()->getEntryTypeById(6);
        $entryType->handle = 'qualification';
        $entryType->name = 'Qualification';
        Craft::$app->getSections()->saveEntryType($entryType);
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\EntryTypeNotFoundException
     */
    private function _updateCpdType()
    {
        # remove fields if they were created already and might have changed in development
        MigrationHelper::deleteFields(['cycleStartMonth', 'cycleDurationValue', 'cycleDurationType', 'cycleDurationLength', 'cycleGrace', 'targetHours', 'targetPoints']);
        $cycleStartDate =  Craft::$app->getFields()->getFieldByHandle('cycleStartDate');

        $cycleUserStartDate = MigrationHelper::createField(
            'craft\fields\LightSwitch',
            'Cycle User Start Date',
            'cycleUserStartDate',
            4,
            ['default' => ''],
            'Select if cycles should begin at user start date.'
        );

        $cycleDurationType = MigrationHelper::createField(
            'craft\fields\DropDown',
            'Cycle Duration Type',
            'cycleDurationType',
            4,
            [
                'options' => [
                    ["label" => "Year", "value"  => "year", "default" => "1"],
                    ["label" => "Quarter", "value"  => "quarter"],
                    ["label" => "Month", "value"  => "month", ],
                    ["label" => "Open", "value"  => "open", ]
                ]
            ],
            'Select Open if the CPD cycle is on-going.'
        );

        $cycleDurationValue = MigrationHelper::createField (
            'craft\fields\Number',
            'Cycle Duration Length',
            'cycleDurationLength',
            4,
            [
                'defaultValue' => 1,
                'min' => 1,
                'max' => ''
            ],
            'Enter the cycle length i.e. 2 years, 4 months etc.'
        );

        $cycleGrace = MigrationHelper::createField (
            'craft\fields\Number',
            'Cycle Grace Days',
            'cycleGrace',
            4,
            [
                'defaultValue' => 0,
                'min' => 0,
                'max' => ''
            ],
            'Enter the number of days after the end of the cycle that results will be accepted.'
        );

        $targetHours = MigrationHelper::createField (
            'craft\fields\Number',
            'Target Hours',
            'targetHours',
            4,
            [
                'defaultValue' => 1,
                'min' => 1,
                'max' => ''
            ]
        );

        $targetPoints = MigrationHelper::createField (
            'craft\fields\Number',
            'Target Points',
            'targetPoints',
            4,
            [
                'defaultValue' => 1,
                'min' => 1,
                'max' => ''
            ]
        );

        if (false == $cpdEntryType = MigrationHelper::getEntryTypeByHandle('cpd')) {
            $cpdEntryType = new EntryType();
            $cpdEntryType->sectionId = 6;
            $cpdEntryType->name = 'CPD';
            $cpdEntryType->handle = 'cpd';
        }
        else {
          ## delete layout previously made in development
          /* $existingLayout = $cpdEntryType->getFieldLayout();
          if ($existingLayout && $existingLayout->id) {
              Craft::$app->getFields()->deleteLayout($existingLayout);
          }
          */
        }

        ## get qualification field layout array
        $entryType = Craft::$app->getSections()->getEntryTypeById(6);
        $fieldLayoutArray = MigrationHelper::getFieldLayoutArray($entryType);

        $fieldLayoutArray['Cycle'] = [
            $cycleUserStartDate->id,
            $cycleStartDate->id,
            $cycleDurationType->id,
            $cycleDurationValue->id,
            $cycleGrace->id,
            $targetPoints->id,
            $targetHours->id
        ];

        $requiredFields = [$this->_fieldId('moduleGroup'), $cycleStartDate->id, $cycleDurationValue->id];

        ## rebuild field layout
        $cpdFieldLayout = Craft::$app->getFields()->assembleLayout($fieldLayoutArray, $requiredFields);
        $cpdFieldLayout->type = Entry::class;
        Craft::$app->getFields()->saveLayout($cpdFieldLayout);

        ## save the entry type
        $cpdEntryType->setFieldLayout($cpdFieldLayout);
        Craft::$app->getSections()->saveEntryType($cpdEntryType);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m200120_163920_update_modules cannot be reverted.\n";
        return false;
    }
}
