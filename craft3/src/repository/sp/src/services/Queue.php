<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\services;

use Craft;
use craft\base\Component;

use lantra\sp\records\Queue as QueueRecord;

class Queue extends Component
{
    private $queue = [];

    /**
     * Queue constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->queue = QueueRecord::find()->orderBy('priority', 'dateCreated')->all();
    }

    /**
     *
     */
    public function get() {
        foreach ($this->queue as $key => $job) {
            // only set elements once...!
            if (isset($job['element'])) {
                break;
            }
            $element = craft()->elements->getElementById($job['elementId']);
            $this->queue[$key]['element'] = $element;
        }
        return $this->queue;
    }

    /**
     * @param $elementId
     * @param $priority
     */
    public function add($elementId, $priority = 1) {
        // only one job per element
        if ($this->job($elementId) != null) {
            return;
        }
        $job = [
            'elementId' => $elementId,
            'status' => 'pending',
            'priority' => $priority,
            'dateCreated' => DateTimeHelper::currentTimeForDb()
        ];
        Craft::$app->db->createCommand()->insert('lantra_queue', $job);
    }

    /**
     * @param $elementId
     * @return bool
     */
    public function job($elementId) {
        return Craft::$app->db->createCommand()
            ->select()
            ->from('lantra_queue')
            ->where(['elementId' => $elementId])
            ->queryRow();
    }

    /**
     * @param $elementId
     * @param string $status
     */
    public function status($elementId, $status = 'running') {
        Craft::$app->db->createCommand()
            ->update('lantra_queue', ['status' => $status], ['elementId' => $elementId]);
    }

    /**
     * called by cron job every minute
     */
    public function next() {
        $job = array_shift($this->queue);
        // expire jobs four hours old
        $expired = $job['dateCreated'] < (time() - 14400);
        if ($job['status'] == 'running' && $expired) {
            $this->delete($job['elementId']);
        }
        if ($job['status'] == 'pending') {
            $this->status($job['elementId'], 'running');
            $this->run($job['elementId']);
        }
    }

    /**
     * @param $elementId
     */
     public function run($elementId) {
        if (null == $entry = craft()->entries->getEntryById($elementId)) {
            return;
        }
        // only works with reports
        if ($entry->sectionId == 13) {
            Lantra::$app->reports->runCustomReport($entry);
        }
    }

    /**
     * @param $elementId
     */
    public function delete($elementId) {
        Craft::$app->db->createCommand()->delete('lantra_queue', ['elementId' => $elementId]);
    }

    /**
     *
     */
    public function clear() {
        Craft::$app->db->createCommand()->truncateTable('lantra_queue');
    }
}