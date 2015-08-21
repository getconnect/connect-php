<?php
namespace Connect;

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
		
		$definitionPad = self::pad(json_encode($definition),16);
	
		if (mcrypt_generic_init($cipher, $masterKey, $iv) != -1) {
	
			$cipherText = mcrypt_generic($cipher,$definitionPad );
			mcrypt_generic_deinit($cipher);
			return sprintf("%s-%s",bin2hex($iv),bin2hex($cipherText));
	
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
		$ivData = explode('-',$encryptedKey);
		$ivHex =$ivData[0];
		$iv = hex2bin($ivHex);
		
		$data_hex = $ivData[1];
		$data = hex2bin($data_hex);
	
		if (mcrypt_generic_init($cipher, $masterKey, $iv) != -1) {
			$decrypted = mdecrypt_generic($cipher, $data);	
			mcrypt_generic_deinit($cipher);
			mcrypt_module_close($cipher);
	
			return self::unpad($decrypted,16);
		}
	
	}
	
	/**
	* Pad an input string for encryption
	* @text string the string to add padding to
	* @blocksize int
	*/
	
	private static function pad($text, $blocksize) {

	
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}
	
	/**
	* Unpad an input string
	* @text string the string to add padding to
	* @blocksize int
	*/
	
	private static function unpad($text, $blocksize) {
		
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
		return substr($text, 0, - $pad);
	}
}


	
	
	