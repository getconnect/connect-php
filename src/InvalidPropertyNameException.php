<?php

namespace Connect;


class InvalidPropertyNameException extends \LogicException {

    /**
     * Instantiates a new InvalidPropertyNameException.
     * @param array $errorDetails the details of the error
     */
    public function __construct($errorDetails) {

        $errorMessage = "";

        foreach ($errorDetails as $key => $value) {
            if (strlen($errorMessage) < 1) {
                $errorMessage .= PHP_EOL;
            }
            $errorMessage .= $key . " field invalid. " . $value;
        }

        parent::__construct($errorMessage);
    }

}
