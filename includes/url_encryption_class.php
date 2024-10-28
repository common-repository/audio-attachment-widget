<?php
// this is the class used to encrypt and decrypt the URL strings using our key

class MyEncryption {

    
	private $key = 'MIGfMA 0GCSqGSIb3D QEBAQUAA4 GNADCBiQKBgQDJmhAL 93uF2NO0bIfW/U4PAS oPEe6gVOkwI23Vek1Vw81Q91Cte D5Bdh4nEPjYigEtqpVSTRDvRjseA4dnkpfVXv LhVXpsUG5Nc7lxSMhO7jj0uiOQMJHQNeP3GCuV3p6gqn3p5s0vsvW6b5vQy7Iuny +x0PfzIU74DnsB0sCQIDAQAB';

    public function enc_encrypt($string) {
    	$result = '';
    	for($i = 0; $i < strlen($string); $i++) {
    		$char = substr($string, $i, 1);
    		$keychar = substr($this->key, ($i % strlen($this->key))-1, 1);
    		$char = chr(ord($char) + ord($keychar));
    		$result .= $char;
    	}

    	return base64_encode($result);
	}

    public function enc_decrypt($string) {
    	$result = '';
    	$string = base64_decode($string);


    	for($i = 0; $i < strlen($string); $i++) {
    		$char = substr($string, $i, 1);
    		$keychar = substr($this->key, ($i % strlen($this->key))-1, 1);
    		$char = chr(ord($char) - ord($keychar));
    		$result .= $char;
    	}

    	return $result;
	}
}
?>