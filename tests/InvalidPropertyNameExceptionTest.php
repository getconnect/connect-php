<?php

namespace Connect\Tests;


use Connect\InvalidPropertyNameException;

class InvalidPropertyNameExceptionTest extends \PHPUnit_Framework_TestCase {

    public function testThatTheExceoptionMessageContainsAllFields() {

        $fieldName1 = 'tp_test';
        $errorMessage1 = 'Property names starting with tp_ are reserved and cannot be set.';

        $fieldName2 = 'arbitraryName';
        $errorMessage2 = 'Some arbitrary error message';

        $errorDetails = [
            $fieldName1 => $errorMessage1,
            $fieldName2 => $errorMessage2,
        ];

        $exception = new InvalidPropertyNameException($errorDetails);

        $this->assertContains($fieldName1, $exception->getMessage());
        $this->assertContains($errorMessage1, $exception->getMessage());

        $this->assertContains($fieldName2, $exception->getMessage());
        $this->assertContains($errorMessage2, $exception->getMessage());

    }

}
