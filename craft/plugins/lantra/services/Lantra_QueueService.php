<?php
namespace Craft;

class Lantra_QueueService extends BaseApplicationComponent
{
    private $queue = [];

    /**
     * Lantra_QueueService constructor.
     */
    public function __construct() {
        $this->queue = craft()->db->createCommand()
            ->select()
            ->order(['priority', 'dateCreated'])
            ->where('status', 'pending')
            ->orWhere('status', 'running')
            ->from('lantra_queue')
            ->queryAll();
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
        craft()->db->createCommand()->insert('lantra_queue', $job);
    }

    /**
     * @param $elementId
     * @return bool
     */
    public function job($elementId) {
        return craft()->db->createCommand()
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
        craft()->db->createCommand()
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
            $this->failed($job['elementId']);
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
            craft()->lantra_reports->runCustomReport($entry);
        }
    }

    /**
     * @param $elementId
     */
    public function failed($elementId) {
        $entry = craft()->entries->getEntryById($elementId);
        $message = ($entry ? $entry->title : 'Unknown job ' . $elementId) . ' failed to complete.';
        craft()->lantra_notify->notifyAdmin('Failed Job', $message);
        $this->status($elementId, 'failed');
    }

    /**
     * @param $elementId
     */
    public function success($elementId) {
        $this->status($elementId, 'success');
    }

    /**
     * @param $elementId
     */
    public function delete($elementId) {
        craft()->db->createCommand()->delete('lantra_queue', ['elementId' => $elementId]);
    }

    /**
     *
     */
    public function clear() {
        craft()->db->createCommand()->truncateTable('lantra_queue');
    }
}