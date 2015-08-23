<?php
namespace Connect;

/**
* Security class for utility methods used for encryption of filtered keys 
*/
class Security {

    /**
    * Encrypt filtered key for use with the Connect API
    * @param array $definition The filtered key definition
    * @param string $masterKey The master key for the Connect project
    * @return String
    */
    public static function generateFilteredKey($definition, $masterKey) {
        $cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        $ivSize = mcrypt_enc_get_iv_size($cipher);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        
        $definitionWithPading = self::_pad(json_encode($definition),16);
    
        if (mcrypt_generic_init($cipher, $masterKey, $iv) != -1) {
            $cipherText = mcrypt_generic($cipher,$definitionWithPading);
            mcrypt_generic_deinit($cipher);
            $encrypted = sprintf("%s-%s",bin2hex($iv),bin2hex($cipherText)); 
            
            return $encrypted;
        }
    }
    
    /**
    * Decrypt filtered key generated via the encryptFukteredKey function
    * @param array $definition The filtered key definition
    * @param string $masterKey The master key for the Connect project
    * @return String
    */
    public static function decryptFilteredKey($encryptedKey, $masterKey){
        $cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        $ivAndFilter = explode('-',$encryptedKey);
        $iv = hex2bin($ivAndFilter[0]);
        
        $filter = hex2bin($ivAndFilter[1]);
    
        if (mcrypt_generic_init($cipher, $masterKey, $iv) != -1) {
            $decrypted = mdecrypt_generic($cipher, $filter);    
            mcrypt_generic_deinit($cipher);
            mcrypt_module_close($cipher);
            
            $decryptedNoPadding = self::_unpad($decrypted,16);
            return $decryptedNoPadding;
        }
    }
    
    /**
    * Pad an input string for encryption
    * @text string the string to add padding to
    * @blocksize int
    */
    private static function _pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        $textWithPadding = $text . str_repeat(chr($pad), $pad); 
        return $textWithPadding;
    }
    
    /**
    * Unpad an input string
    * @text string the string to add padding to
    * @blocksize int
    */
    private static function _unpad($text, $blocksize) {
        if (empty($text)) {
            return '';
        }
        if (strlen($text) % $blocksize !== 0) {
            return false;
        }
        $pad = ord($text{strlen($text)-1});
        if ($pad > $blocksize || $pad > strlen($text) || $pad === 0) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        $textNoPadding = substr($text, 0, - $pad); 
        return $textNoPadding;
    }
}
