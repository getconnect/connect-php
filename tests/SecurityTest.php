<?php
namespace Connect\Tests;

use Connect\Connect;
use PHPUnit_Framework_TestCase;

class SecurityTest extends PHPUnit_Framework_TestCase
{
    
    public function testEncryptedFilteredKeyDecryptToTheSameValue(){
        $masterKey = '00000000000000000000000000000000';        
        $keyDefinition = [
            'filters' => [
                'type' => 'cycling'
            ],
            "canQuery" => True,
            "canPush" => True
        ];

        $originalValue = json_encode($keyDefinition);
        $filtered_key = Connect::generateFilteredKey($keyDefinition, $masterKey);
        $decrypted = Connect::decryptFilteredKey($filtered_key, $masterKey);
        
        assert($originalValue == $decrypted);
        
    }

}