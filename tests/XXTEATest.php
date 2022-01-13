<?php

namespase xtea/tests;

use xtea/Xxtea;

class XXTEATest extends PHPUnit_Framework_TestCase {
    public function testEncrypt() {
        $str = "Hello World!";
    	$key = "1234567890";
    	$encrypt_data = Xxtea::encrypt($str, $key);
        $this->assertEquals(base64_encode($encrypt_data), "AkwSi8IEfZxDejCdKQBLWL7w1zGktADVekoAXQ==");
    }
    public function testDecrypt() {
        $str = "Hello World!";
    	$key = "1234567890";
    	$encrypt_data = Xxtea::encrypt($str, $key);
    	$decrypt_data = Xxtea::decrypt($encrypt_data, $key);
        $this->assertEquals($decrypt_data, $str);
    }
}
