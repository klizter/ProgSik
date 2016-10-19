<?php

namespace tdt4237\webapp;

use Symfony\Component\Config\Definition\Exception\Exception;

class Hash
{  
    static $salt = '';

    public function __construct()
    {
        $salt = base64_encode(openssl_random_pseudo_bytes(30));
    }

    public static function make($plaintext)
    {
        
        return hash('sha256', $plaintext . Hash::$salt);
    }

    public function check($plaintext, $hash)
    {
        return $this->make($plaintext) === $hash;
    }

}
