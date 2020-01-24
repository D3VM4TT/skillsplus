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

use lantra\sp\Plugin as Lantra;
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
        if (Craft::$app->db->tableExists('{{%lantra_queue}}')) {
            $this->queue = QueueRecord::find()
                ->where(['status' => 'pending'])
                ->orWhere(['status' => 'running'])
                ->orderBy('priority, dateCreated')
                ->all();
        }
    }

    /**
     *
     */
    public function get()
    {
        foreach ($this->queue as $key => $job) {
            ## only set elements once...!
            if (isset($job['element'])) {
                break;
            }
            $element = Craft::$app->elements->getElementById($job['elementId']);
            $this->queue[$key]->element = $element;
        }
        return $this->queue;
    }

    /**
     * @param $elementId
     * @param $priority
     */
    public function add($elementId, $priority = 1)
    {
        ## only one job per element
        if ($this->job($elementId) != null) {
            return;
        }
        $job = new QueueRecord([
            'elementId'     => $elementId,
            'status'        => 'pending',
            'priority'      => $priority,
        ]);
        $job->save();
    }

    /**
     * @param $elementId
     * @return QueueRecord|null
     */
    public function job($elementId)
    {
        return QueueRecord::findOne(['elementId' => $elementId]);
    }

    /**
     * @param $elementId
     * @param string $status
     */
    public function status($elementId, $status = 'running')
    {
        if (false != $job = $this->job($elementId)) {
            $job->status = $status;
            $job->save();
        }
    }

    /**
     * called by cron job every minute
     */
    public function next()
    {
        $job = array_shift($this->queue);
        ## expire jobs twelve hours old
        $expired = $job['dateCreated'] < (time() - 43200);
        if ($job['status'] == 'running' && $expired) {
            $this->expired($job['elementId']);
        }
        if ($job['status'] == 'pending') {
            $this->status($job['elementId'], 'running');
            $this->run($job['elementId']);
        }
    }

    /**
     * @param $elementId
     */
     public function run($elementId)
     {
        if (null == $entry = Craft::$app->entries->getEntryById($elementId)) {
            return;
        }
        try {
            ## only works with reports
            if ($entry->sectionId == 13) {
                $response = Lantra::$app->reports->runCustomReport($entry);
            }
        }
        catch(\Exception $e) {
            $message = ($entry ? $entry->title : 'Unknown job ' . $elementId) . ' failed to run. ' . $e->getMessage();
            Lantra::$app->notify->notifyAdmin('Failed Job', $message);
        }
    }

    /**
     * @param $elementId
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function expired($elementId)
    {
        $entry = Craft::$app->entries->getEntryById($elementId);
        $message = ($entry ? $entry->title : 'Unknown job ' . $elementId) . ' failed to complete in 12 hours.';
        Lantra::$app->notify->notifyAdmin('Expired Job', $message);
        $this->delete($elementId);
    }

    /**
     * @param $elementId
     */
    public function success($elementId)
    {
        $this->status($elementId, 'success');
    }

    /**
     * @param $elementId
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete($elementId)
    {
        if (false != $job = $this->job($elementId)) {
            $job->delete();
        }
    }

    /**
     * @throws \yii\db\Exception
     */
    public function clear()
    {
        Craft::$app->db->createCommand()->truncateTable('{{%lantra_queue}}')->execute();
    }
}