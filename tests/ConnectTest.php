<?php


namespace Connect\Tests;

use Connect\Client;
use Connect\ConfigurationException;
use Connect\Connect;
use DateTime;
use DateTimeZone;
use Httpful\Request;
use PHPUnit_Framework_TestCase;


class ConnectTest extends PHPUnit_Framework_TestCase
{

    public function testThatPushingBeforeInitializationThrowsAnExceptionWithMessage() {
        $purchase = [
            'customer' => [
                'firstName' => 'Tom',
                'lastName' => 'Smith'
            ],
            'product' => '12 red roses',
            'purchasePrice' => 34.95,
        ];
        try {
            Connect::push('purchases', $purchase);
            $this->fail('Pushing before initializing should throw an exception');
        } catch(ConfigurationException $exception) {
            $this->assertNotNull($exception->getMessage());
        }
    }

}
