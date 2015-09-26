<?php

/**
 * Greavatar Exception Class
 * 
 * @package   Gravatar
 * @author    Ravi Kumar
 * @version   0.1.0    
 * @copyright Copyright (c) 2014, Ravi Kumar
 * @license   https://github.com/ravikumar8/Gravatar/blob/master/LICENSE MIT
 **/
namespace Gravatar\Exception;

use Gravatar\Exception;

class InvalidFormatException extends Exception
{
    public function __construct($message = '', $code = 0, Exception $previous = null) 
    {
        if(empty($message) ) {
            $message    =    'Invalid Format.'; 
        }
        parent::__construct($message, $code, $previous);
    }

    public function __toString() 
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
