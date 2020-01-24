<?php
namespace Craft;

class Mailer_SchedulerRecord extends BaseRecord
{
    
    /**
     * @access protected
     * @return string
     */
    public function getTableName()
    {
        return 'mailer_scheduler';
    }


    /**
     * @access protected
     * @return array
     */
    protected function defineAttributes()
    {
        return array(
            'subject'       => AttributeType::String,
            'htmlBody'      => AttributeType::String,
            'status'        => array(AttributeType::Enum, 'values' => "finished,running,failed"),
            'description'   => AttributeType::String,

            'dateCreated'   => AttributeType::DateTime,
            'dateFinished'  => AttributeType::DateTime,

            'success'       => AttributeType::Number,
            'errors'        => AttributeType::Mixed,

            'postData'       => AttributeType::Mixed,
            'dateToSend'   => AttributeType::DateTime,
        );
    }
}