<?php

namespace xxtea\tests;

use xxtea\Xxtea;
use PHPUnit_Framework_TestCase;

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

    public function testEncryptBase64() {
        $str = "Hello World!";
        $key = "1234567890";
        $encrypt_data = Xxtea::encrypt_base64($str, $key);
        $this->assertEquals("AkwSi8IEfZxDejCdKQBLWL7w1zGktADVekoAXQ==", $encrypt_data);
    }

    public function testDecryptBase64() {
        $str = "Hello World!";
        $key = "1234567890";
        $encrypt_data = Xxtea::encrypt_base64($str, $key);
        $decrypt_data = Xxtea::decrypt_base64($encrypt_data, $key);
        $this->assertEquals($decrypt_data, $str);
    }

    public function testEncryptDataToBase64() {
        $array = ['a' => "Hello", 'b' => "World!"];
        $key = "1234567890";
        $encrypt_data = Xxtea::encrypt_data_to_base64($array, $key);
        $this->assertEquals(
            "an0io4tFOuXSo2ht4wVd8zzsgZvaTo5Jq5oU6MGhApOxjLJpN5qNiRmrXM/9Mc8F7h7MdtuqFZZISX33+mmlrw==",
            $encrypt_data
        );
    }

    public function testDecryptDataFromBase64() {
        $array = ['a' => "Hello", 'b' => "World!"];
        $key = "1234567890";
        $encrypt_data = Xxtea::encrypt_data_to_base64($array, $key);
        $decrypt_data = Xxtea::decrypt_data_from_base64($encrypt_data, $key);
        $this->assertEquals($array['a'], $decrypt_data['a']);
    }
}
