<?php
namespace Craft;

class Lantra_QueueService extends BaseApplicationComponent
{
    private $queue = [];

    /**
     * Lantra_QueueService constructor.
     */
    public function __construct() {
        $this->queue = (array) craft()->lantra_settings->getSetting('queue');
    }

    /**
     *
     */
    public function save() {
        craft()->lantra_settings->saveSetting('queue', $this->queue);
    }

    /**
     *
     */
    public function clear() {
        $this->queue = [];
        $this->save();
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
     */
    public function add($elementId) {
        // only one job per element
        if ($this->job($elementId) != null) {
            return;
        }
        $job = [
            'elementId' => $elementId,
            'status' => 'pending',
            'dateCreated' => DateTimeHelper::currentTimeForDb()
        ];
        $this->queue[] = $job;
        $this->save();
    }

    /**
     * @param $elementId
     * @return bool
     */
    public function job($elementId) {
        foreach ($this->queue as $key => $job) {
            if ($job['elementId'] == $elementId) {
                return $job;
            }
        }
        return null;
    }

    /**
     * @param $elementId
     */
    public function delete($elementId) {
        foreach ($this->queue as $key => $job) {
            if ($job['elementId'] == $elementId) {
                unset($this->queue[$key]);
                $this->save();
                break;
            }
        }
    }

    /**
     * called by cron job every minute
     */
    public function next() {
        $job = array_shift($this->queue);
        // expire jobs four hours old
        $expired = $job['dateCreated'] < (time() - 14400);
        if ($job['status'] == 'running' && $expired) {
            $this->save();
        }
        if ($job['status'] == 'pending') {
            $job['status'] = 'running';
            array_unshift($this->queue, $job);
            $this->run($job['elementId']);
            $this->save();
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
}