<?php

namespace lantra\sp\migrations;

use Craft;
use craft\db\Migration;

/**
 * m200408_095422_fix_elements_layouts migration.
 */
class m200101_000000_fix_elements_layouts extends Migration
{
    private $_layoutUpdate = [];
    private $_n = 1;

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        # fix entry types
        $entryTypes = [
            'company'       => 389,
            'team'          => 71,
            'module'        => 269,
            'qualification' => 269, # if it hasn't been changed yet...!
            'unit'          => 282,
            'unitResult'    => 390,
            'attempt'       => 122,
            'moduleResult'  => 391,
            'reports'       => 383,
            'pages'         => 258,
            'userResult'    => 392,

        ];

        $mysql = 'SELECT fieldLayoutId, handle FROM {{%entrytypes}}';
        $result = Craft::$app->db->createCommand($mysql)->query();
        foreach ($result as $row) {
            if (isset($entryTypes[$row['handle']])){
                $this->_addLayoutUpdate($row['fieldLayoutId'], $entryTypes[$row['handle']], $row['handle']);
            }
        }

        # query matrix blocks
        $matrixBlockTypes = [
            'unitGroup'         => 74,
            'trueFalse'         => 316,
            'choices'           => 317,
            'text'              => 318,
            'answer'            => 97,
            'paypal'            => 147,
            'slide'             => 171,
            'columns'           => 247,
            'userCustomField'   => 277
        ];

        $mysql = 'SELECT fieldLayoutId, handle FROM {{%matrixblocktypes}}';
        $result = Craft::$app->db->createCommand($mysql)->query();
        foreach ($result as $row) {
            if (isset($matrixBlockTypes[$row['handle']])){
                $this->_addLayoutUpdate($row['fieldLayoutId'], $matrixBlockTypes[$row['handle']], $row['handle']);
            }
        }

        # query global set (single - globalsTheme)
        $mysql = 'SELECT fieldLayoutId FROM {{%globalsets}} where handle = "globalsTheme"';
        $result = Craft::$app->db->createCommand($mysql)->queryOne();
        if ($result) {
            $this->_addLayoutUpdate($result['fieldLayoutId'], 268, 'globalsTheme');
        }

        # query super table blocks
        $superTableBlockTypes = [
            'columnLayout'      => 331,
            'resultComments'    => 280,
            'resultCustom'      => 297
        ];

        $mysql = 'SELECT s.fieldLayoutId, f.handle FROM {{%supertableblocktypes}} AS s JOIN {{%fields}} AS f on f.id = s.fieldId';
        $result = Craft::$app->db->createCommand($mysql)->query();
        foreach ($result as $row) {
            if (isset($superTableBlockTypes[$row['handle']])){
                $this->_addLayoutUpdate($row['fieldLayoutId'], $superTableBlockTypes[$row['handle']], $row['handle']);
            }
        }

        # query volumes
        $volumes = [
            'evidence'      => 323,
            'uploads'       => 289,
            'data'          => 324,
            'theme'         => 291
        ];

        $mysql = 'SELECT fieldLayoutId, handle FROM {{%volumes}}';
        $result = Craft::$app->db->createCommand($mysql)->query();
        foreach ($result as $row) {
            if (isset($volumes[$row['handle']])){
                $this->_addLayoutUpdate($row['fieldLayoutId'], $volumes[$row['handle']], $row['handle']);
            }
        }

        # query category groups
        $categoryGroups = [
            'roles'         => 393,
            'moduleGroups'  => 302
        ];

        $mysql = 'SELECT fieldLayoutId, handle FROM {{%categorygroups}}';
        $result = Craft::$app->db->createCommand($mysql)->query();
        foreach ($result as $row) {
            if (isset($categoryGroups[$row['handle']])){
                $this->_addLayoutUpdate( $row['fieldLayoutId'], $categoryGroups[$row['handle']], $row['handle']);
            }
        }

        $this->_updateElements();
        $this->_fixGlobalSets();

    }

    private function _fixGlobalSets() {
        ## make sure globalset table has correct layoutId
        $mysql = 'UPDATE {{%globalsets}} SET fieldLayoutId = 268 where handle = "globalsTheme"';
        Craft::$app->db->createCommand($mysql)->execute();
        $mysql = 'DELETE FROM {{%globalsets}} WHERE handle != "globalsTheme"';
        Craft::$app->db->createCommand($mysql)->execute();
    }

    /**
     * @param $oldId
     * @param $newId
     * @param $handle
     */
    private function _addLayoutUpdate($oldId, $newId, $handle)
    {
        if ($oldId && $newId && ($oldId != $newId)) {
            $this->_layoutUpdate['00' . $this->_n] = [$oldId, $newId];
            $this->_n++;
            Craft::info('update fieldLayoutId (' . $handle . ') ' . $oldId . ' -> ' . $newId, __METHOD__);
        }
    }

    /**
     *
     */
    private function _updateElements()
    {
        $key = 'craft_elements_fieldLayoutId_fk';
        if ($this->_hasForeignKey($key)) {
            $this->dropForeignKey($key, '{{%elements}}');
        }

        # update all users
        $this->update('{{%elements}}', ['fieldLayoutId' => 401], ['type' => 'craft\elements\User']);

        if (count($this->_layoutUpdate)) {
            # update elements to temp values
            foreach ($this->_layoutUpdate as $temp => $values) {
                $this->update('{{%elements}}', ['fieldLayoutId' => $temp], ['fieldLayoutId' => $values[0]]);
            }
            # update elements to new values
            foreach ($this->_layoutUpdate as $temp => $values) {
                $this->update('{{%elements}}', ['fieldLayoutId' => $values[1]], ['fieldLayoutId' => $temp]);
            }
        }

        # $this->addForeignKey(null, '{{%elements}}', ['fieldLayoutId'], '{{%fieldlayouts}}', ['id'], 'SET NULL', null);
        Craft::info('update elements (' . count($this->_layoutUpdate) . ') fieldLayoutId', __METHOD__);
    }

    /**
     * @return bool
     * @throws \yii\base\NotSupportedException
     */
    private function _hasForeignKey($key)
    {
        $indexes = $this->db->getSchema()->findIndexes('{{%elements}}');
        return isset($indexes[$key]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m200408_095422_fix_elements_layouts cannot be reverted.\n";
        return false;
    }
}
