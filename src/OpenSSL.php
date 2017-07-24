<?php

namespace Piesync\Partner;

class OpenSSL
{
    public function genRSA($bitNumber = 2048)
    {
        $config = [
            'private_key_bits' => $bitNumber,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];

        $resource = $this->newPrivateKey($config);
        return $resource;
    }

    public function getPrivateKey($resource)
    {
        $privateKey = null;
        openssl_pkey_export($resource, $privateKey);
        return $privateKey;
    }

    public function getPublicKey($resource)
    {
        $publicKey = openssl_pkey_get_details($resource);

        return $publicKey['key'];
    }


    public function newPrivateKey($config)
    {
        return openssl_pkey_new($config);
    }
}
