<?php
/**
 * Lantra Skills+ Base for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2022 Coffee Bean Design
 */

namespace lantra\spbase\services\gql\exceptions;

class GraphQLError extends \Exception
{
    public function __construct($message, $critical = false, \Throwable $previous = null)
    {
        $code = $critical ? 500 : 400;

        parent::__construct($message, $code, $previous);
    }
}