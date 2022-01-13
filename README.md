# XXTEA for PHP

## Introduction

XXTEA is a fast and secure encryption algorithm. This is a XXTEA library for PHP.

It is different from the original XXTEA encryption algorithm. It encrypts and decrypts string instead of uint32 array, and the key is also string.

## Usage

```php
<?php
use xxtea\Xxtea;

$str = "Hello World!";
$key = "1234567890";
$encrypt_data = Xxtea::encrypt($str, $key);
$decrypt_data = Xxtea::decrypt($encrypt_data, $key);
if ($str == $decrypt_data) {
    echo "success!";
} else {
    echo "fail!";
}
?>
```
