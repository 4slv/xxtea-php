<?php

namespace xxtea;

/**
 * XXTEA encryption implementation class
 */
class Xxtea {

    /**
     * Encryption string
     * @Param String $string
     * @Param String $key encryption key
     * @Param Integer $expire Validity Period (Second)
     * @return string
     */
    public static function encrypt($str, $key, $expire=0) {
        $expire = sprintf('%010d', $expire ? $expire + time():0);
        $str    =   $expire.$str;
        $v = self::str2long($str, true);
        $k = self::str2long($key, false);
        $n = count($v) - 1;

        $z = $v[$n];
        $y = $v[0];
        $delta = 0x9E3779B9;
        $q = floor(6 + 52 / ($n + 1));
        $sum = 0;
        while (0 < $q--) {
            $sum = self::int32($sum + $delta);
            $e = $sum >> 2 & 3;
            for ($p = 0; $p < $n; $p++) {
                $y = $v[$p + 1];
                $d = isset($k[$p & 3 ^ $e]) ? $k[$p & 3 ^ $e] : 0;
                $mx = self::int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ self::int32(($sum ^ $y) + ($d ^ $z));
                $z = $v[$p] = self::int32($v[$p] + $mx);
            }
            $y = $v[0];
            $d = isset($k[$p & 3 ^ $e]) ? $k[$p & 3 ^ $e] : 0;
            $mx = self::int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ self::int32(($sum ^ $y) + ($d ^ $z));
            $z = $v[$n] = self::int32($v[$n] + $mx);
        }
        return self::long2str($v, false);
    }

    /**
     * Encryption string to base64
     * @Param String $string
     * @Param String $key encryption key
     * @Param Integer $expire Validity Period (Second)
     * @return string
     */
    public static function encrypt_base64($str, $key, $expire=0)
    {
        return base64_encode(self::encrypt($str, $key, $expire));
    }

    /**
     * Encryption data => base64
     * @Param mixed $data
     * @Param String $key encryption key
     * @Param Integer $expire Validity Period (Second)
     * @return string
     */
    public static function encrypt_data_to_base64($data, $key, $expire=0)
    {
        return self::encrypt_base64(serialize($data), $key, $expire);
    }

    /**
     * Decrypt string
     * @Param String $string
     * @Param String $key encryption key
     * @return string
     */
    public static function decrypt($str, $key) {
        $v = self::str2long($str, false);
        $k = self::str2long($key, false);
        $n = count($v) - 1;

        $z = $v[$n];
        $y = $v[0];
        $delta = 0x9E3779B9;
        $q = floor(6 + 52 / ($n + 1));
        $sum = self::int32($q * $delta);
        while ($sum != 0) {
            $e = $sum >> 2 & 3;
            for ($p = $n; $p > 0; $p--) {
                $z = $v[$p - 1];
                $d = isset($k[$p & 3 ^ $e]) ? $k[$p & 3 ^ $e] : 0;
                $mx = self::int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ self::int32(($sum ^ $y) + ($d ^ $z));
                $y = $v[$p] = self::int32($v[$p] - $mx);
            }
            $z = $v[$n];
            $d = isset($k[$p & 3 ^ $e]) ? $k[$p & 3 ^ $e] : 0;
            $mx = self::int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ self::int32(($sum ^ $y) + ($d ^ $z));
            $y = $v[0] = self::int32($v[0] - $mx);
            $sum = self::int32($sum - $delta);
        }
        $data   = self::long2str($v, true);
        $expire = substr($data,0,10);
        if($expire > 0 && $expire < time()) {
            return '';
        }
        $data   = substr($data,10);
        return $data;
    }

    /**
     * Decrypt base64 string
     * @Param String $string base64 string
     * @Param String $key encryption key
     * @return string
     */
    public static function decrypt_base64($str, $key)
    {
        return self::decrypt(base64_decode($str), $key);
    }

    /**
     * Decrypt base64 => data
     * @Param String $string base64 string
     * @Param String $key encryption key
     * @return mixed
     */
    public static function decrypt_data_from_base64($str, $key)
    {
        return unserialize(self::decrypt_base64($str, $key));
    }

    private static function long2str($v, $w) {
        $len = count($v);
        $s = array();
        for ($i = 0; $i < $len; $i++) {
            $s[$i] = pack("V", $v[$i]);
        }
        if ($w) {
            return substr(join('', $s), 0, $v[$len - 1]);
        }else{
            return join('', $s);
        }
    }

    private static function str2long($s, $w) {
        $v = unpack("V*", $s. str_repeat("\0", (4 - strlen($s) % 4) & 3));
        $v = array_values($v);
        if ($w) {
            $v[count($v)] = strlen($s);
        }
        return $v;
    }

    private static function int32($n) {
        while ($n >= 2147483648) $n -= 4294967296;
        while ($n <= -2147483649) $n += 4294967296;
        return (int)$n;
    }

}
