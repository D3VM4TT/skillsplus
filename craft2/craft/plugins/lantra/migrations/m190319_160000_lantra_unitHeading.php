<?php
namespace Craft;

class m190319_160000_lantra_unitHeading extends BaseMigration
{
    public function safeUp()
    {
        $unitHeadingField = craft()->lantra_migration->addField(4, 'Unit Heading', 'unitHeading', 'PlainText', ['multiline' => true,'initialRows' => 4]);
        $fieldLayout = craft()->lantra_migration->addLayoutField(282, 344, $unitHeadingField->id, false, 8);
    }
}