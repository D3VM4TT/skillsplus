<?php
/**
 * Lantra Skills+ Base for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2022 Coffee Bean Design
 */

namespace lantra\spbase\services\gql;

class Response
{
    public $valid;

    /**
     * @var
     */
    protected $data;

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @param $response
     */
    public function __construct($response)
    {
        if (isset($response->data)) {
            $this->data = $response->data;
        }

        if (isset($response->errors)) {
            $this->errors = $response->errors;
        }
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return (bool)count($this->errors());
    }

    /**
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->data->{$name};
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data->{$name} = $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data->{$name});
    }

    /**
     * @param $name
     */
    public function __unset($name)
    {
        unset($this->data->{$name});
    }
}