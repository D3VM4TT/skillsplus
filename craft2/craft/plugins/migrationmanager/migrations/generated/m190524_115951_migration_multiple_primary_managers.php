<?php

namespace Craft;

/**
 * Generated migration
 */
class m190524_115951_migration_multiple_primary_managers extends BaseMigration
{
    /**
     * @return bool
     * @throws \Exception
     */
    public function safeUp()
    {
        $field = craft()->fields->getFieldByHandle('companyPrimaryManager');
        if ($field) {
            $field->setAttribute('handle', 'companyPrimaryManagers');
            $field->setAttribute('name', 'Company Primary Managers');
            $settings = $field->settings;
            $settings['limit'] = null;
            $field->setAttribute('settings', $settings);
            craft()->fields->saveField($field);
        }
        return true;
    }
}
