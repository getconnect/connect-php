<?php

namespace Connect\Tests;

use Connect\Event;
use Connect\InvalidPropertyNameException;
use DateTime;
use DateTimeZone;
use PHPUnit_Framework_TestCase;

class EventTest extends PHPUnit_Framework_TestCase
{

    public function testUsingAReservedPropertyThrowsAnException() {

        $invalidFieldName = 'tp_test';

        $purchase = [
            'customer' => [
                'firstName' => 'Tom',
                'lastName' => 'Smith',
                'time' => new DateTime(null, new DateTimeZone('UTC'))
            ],
            'product' => '12 red roses',
            $invalidFieldName => 'Invalid Event',
            'purchasePrice' => 34.95,
            'timestamp' => new DateTime(null, new DateTimeZone('UTC'))
        ];

        try {
            $event = new Event($purchase);
            $this->fail('Using a reserved property should throw an exception.');
        } catch(InvalidPropertyNameException $exception) {
            $this->assertTrue(true);
            $this->assertContains($invalidFieldName, $exception->getMessage());
        }
    }

    public function testUsingADotInPropertyNameThrowsAnException() {

        $invalidFieldName = 'test.error';

        $purchase = [
            'customer' => [
                'firstName' => 'Tom',
                'lastName' => 'Smith',
                'time' => new DateTime(null, new DateTimeZone('UTC'))
            ],
            'product' => '12 red roses',
            $invalidFieldName => 'Invalid Event',
            'purchasePrice' => 34.95,
            'timestamp' => new DateTime(null, new DateTimeZone('UTC'))
        ];

        try {
            $event = new Event($purchase);
            $this->fail('Using a dot in property name should throw an exception.');
        } catch(InvalidPropertyNameException $exception) {
            $this->assertTrue(true);
            $this->assertContains($invalidFieldName, $exception->getMessage());
        }
    }

    public function testUsingUnderscoreIdInPropertyNameThrowsAnException() {

        $invalidFieldName = '_id';

        $purchase = [
            'customer' => [
                'firstName' => 'Tom',
                'lastName' => 'Smith',
                'time' => new DateTime(null, new DateTimeZone('UTC'))
            ],
            'product' => '12 red roses',
            $invalidFieldName => 'Invalid Event',
            'purchasePrice' => 34.95,
            'timestamp' => new DateTime(null, new DateTimeZone('UTC'))
        ];

        try {
            $event = new Event($purchase);
            $this->fail('Using \'_id\' in property name should throw an exception.');
        } catch(InvalidPropertyNameException $exception) {
            $this->assertTrue(true);
            $this->assertContains($invalidFieldName, $exception->getMessage());
        }
    }

    public function testThatANestedDateIsConvertedToISO8601() {

        $date = new DateTime(null, new DateTimeZone('UTC'));
        $iso8601representation = $date->format(DateTime::ISO8601);

        $purchase = [
            'customer' => [
                'firstName' => 'Tom',
                'lastName' => 'Smith',
                'time' => $date
            ],
            'product' => '12 red roses',
            'purchasePrice' => 34.95
        ];

        $event = new Event($purchase);
        $processedDetails = $event->getDetails();

        $this->assertEquals($iso8601representation, $processedDetails['customer']['time']);
    }

}
