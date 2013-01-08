<?php

/*
 * AsymmetriCrypt - A PHP public key cryptography library
 * 
 * @author    Luciano Longo <luciano.longo@studioigins.net>
 * @copyright (c) Luciano Longo
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @package   AsymmetriCrypt
 */

namespace AsymmetriCrypt;

use AsymmetriCrypt\Key\PublicKey;
use AsymmetriCrypt\Key\PrivateKey;

class Crypter
{
    /**
     * The key contents
     */
    protected $data;

    public static function encrypt($message, PublicKey $pubkey, $url_safe = false) {
        openssl_public_encrypt($message, $out, $pubkey->getKey());
        $out = chunk_split(base64_encode($out));

        if ($url_safe) {
            $out = strtr($out, '+/=', '-_,');
        }

        return $out;
    }

    public static function decrypt($message, PrivateKey $privkey) {
        $message = base64_decode(strtr( $message, '-_,', '+/='));
        openssl_private_decrypt($message, $out, $privkey->getKey());

        return $out;
    }

    public static function sign($message, PrivateKey $privkey, $url_safe = false) {
        if(!openssl_sign($message, $signature, $privkey->getKey())) {
            throw new \Exception("Couldn't sign message!");
        }
        $out = chunk_split(base64_encode($signature));

        if($url_safe) {
            $out = strtr($out, '+/=', '-_,');
        }

        return $out;
    }

    public static function verify($message, $signature, PublicKey $pubkey) {
        $signature = base64_decode(strtr($signature, '-_,', '+/='));
        $result = openssl_verify($message, $signature, $pubkey->getKey());
        if($result == 0) {
            return false;
        }
        if($result == 1) {
            return true;
        }
        throw new \Exception("Couldn't verify message!");
    }

    public static function createPrivateKey($passphrase = null, $bits = 1024) {
        // Make sure is an int
        $bits = (int)$bits;

        // Check size
        if( $bits < 384 ) {
            throw new \Exception("The bits can't be less than 384!");
        }

        // Create Private Key
        $pkey = openssl_pkey_new(array(
            'encrypt_key' => true,
            'private_key_type' =>  OPENSSL_KEYTYPE_RSA, // As of 5.3, php only supports RSA keys creation
            'private_key_bits' => $bits,
        ));
        if(! $pkey) throw new \Exception("Couldn't create private key!");

        return new PrivateKey($pkey, $passphrase);
    }

    public static function loadPrivateKey($key, $passphrase = null) {
        return new PrivateKey($key, $passphrase);
    }

    public static function loadPublicKey($key) {
        return new PublicKey($key);
    }
}