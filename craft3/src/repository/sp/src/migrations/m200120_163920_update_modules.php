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

    /**
     * @return bool|void
     * @throws \Throwable
     * @throws \craft\errors\EntryTypeNotFoundException
     */
    public function safeUp()
    {
        $this->_updateModuleType();
        $this->_updateCpdType();
        $this->_updateModuleResult();
        $this->_updateUnitGroup();
        $this->_updateModule();
        $this->_addUnitHeading();
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

        $fieldsService = Craft::$app->getFields();

        $entryType = MigrationHelper::getEntryTypeByHandle('moduleResult');
        $fieldIds = $entryType->getFieldLayout()->getFieldIds();
        if (!in_array($resultLocked->id, $fieldIds)) {
            $fieldLayoutArray = MigrationHelper::getFieldLayoutArray($entryType);
            $fieldLayoutArray['Result'] = [67, 29, (int)$resultLocked->id];
            $fieldLayoutArray['Cycle'] = [$cycleName->id, $cycleStartDate->id, $cycleFinishDate->id];
            $fieldLayout = $fieldsService->assembleLayout($fieldLayoutArray);
            $fieldLayout->id = $entryType->getFieldLayoutId();
            $fieldLayout->type = 'craft\elements\Entry';
            $fieldsService->saveLayout($fieldLayout);
        }
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
        $fieldIds = $entryType->getFieldLayout()->getFieldIds();
        if (!in_array($unitTooltip->id, $fieldIds)) {
            $fieldLayoutArray = MigrationHelper::getFieldLayoutArray($entryType);
            $fieldLayoutArray['Unit'] = [16, (int)$unitTooltip->id, 18, 39, 130, 37, 74, 192, 194];
            $fieldLayout = $fieldsService->assembleLayout($fieldLayoutArray);
            $fieldLayout->id = $entryType->getFieldLayoutId();
            $fieldLayout->type = 'craft\elements\Entry';
            $fieldsService->saveLayout($fieldLayout);
        }
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

        $columnLayout = $supertableService->getBlockTypeById(2);

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
        $fieldLayoutArray['Content'] = [168, 169, 196, 197, 198, 199, $fieldRequired->id];
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
        $fieldLayoutArray['Content'] = [20, 25, (int) $enableSubmissions->id, (int) $unitGroupUnitValue->id];
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
        $cycleStartMonth = MigrationHelper::createField(
            'craft\fields\DropDown',
            'Cycle Start Month',
            'cycleStartMonth',
            4,
            [
                'options' => [
                    ["label" => "January", "value"  => "1", "default" => "1"],
                    ["label" => "February", "value"  => "2"],
                    ["label" => "March", "value"  => "3"],
                    ["label" => "April", "value"  => "4"],
                    ["label" => "May", "value"  => "5"],
                    ["label" => "June", "value"  => "6"],
                    ["label" => "July", "value"  => "7"],
                    ["label" => "August", "value"  => "8"],
                    ["label" => "September", "value"  => "9"],
                    ["label" => "October", "value"  => "10"],
                    ["label" => "November", "value"  => "11"],
                    ["label" => "December", "value"  => "12"],
                ]
            ]
        );

        $cycleDurationType = MigrationHelper::createField(
            'craft\fields\DropDown',
            'Cycle Duration Type',
            'cycleDurationType',
            4,
            [
                'options' => [
                    ["label" => "Month", "value"  => "month", "default" => "1"],
                    ["label" => "Quarter", "value"  => "quarter"],
                    ["label" => "Year", "value"  => "year"]
                ]
            ]
        );

        $cycleDurationValue = MigrationHelper::createField(
            'craft\fields\Number',
            'Cycle Duration Value',
            'cycleDurationValue',
            4,
            [
                'defaultValue' => 1,
                'min' => 1,
                'max' => ''
            ]
        );

        $cycleGrace = MigrationHelper::createField(
            'craft\fields\Number',
            'Cycle Grace',
            'cycleGrace',
            4,
            [
                'defaultValue' => 0,
                'min' => 0,
                'max' => ''
            ]
        );

        $targetHours = MigrationHelper::createField(
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

        $targetPoints = MigrationHelper::createField(
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

        $postedFieldLayout = [
            'Module' => [166,2,38,181,19],
            'Cycle' => [$cycleStartMonth->id, $cycleStartMonth->id, $cycleDurationType->id, $cycleDurationValue->id, $cycleGrace->id, $targetPoints->id, $targetHours->id],
            'Job Roles' => [28]
        ];

        $requiredFields = [166];

        if (false == $cpdEntryType = MigrationHelper::getEntryTypeByHandle('cpd')) {
            $cpdEntryType = new EntryType();
            $cpdEntryType->sectionId = 6;
            $cpdEntryType->name = 'CPD';
            $cpdEntryType->handle = 'cpd';
        }
        $cpdFieldLayout = Craft::$app->getFields()->assembleLayout($postedFieldLayout, $requiredFields);
        $cpdFieldLayout->type = Entry::class;
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
