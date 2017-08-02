<?php
namespace Piesync\Partner;

use JOSE_JWT;

class TokenGenerator
{

    protected $payload;
    protected $privateKeyFile;
    protected $piesyncPublicKeyFile;


    public function setPayload(Payload $payload)
    {
        $this->payload = $payload;
        return $this;
    }

    public function setPrivateKeyFile($privateKeyFile)
    {
        $this->privateKeyFile = $privateKeyFile;
        return $this;
    }

    public function setPiesyncPublicKeyFile($publicKeyFile)
    {
        $this->piesyncPublicKeyFile = $publicKeyFile;
        return $this;
    }

    public function build()
    {
        $privateKey = file_get_contents($this->privateKeyFile);
        $publicKey = file_get_contents($this->publicKeyFile);

        $jwt = new JOSE_JWT($this->payload->toArray());
        $jws = $jwt->sign($privateKey, 'RS256');
        $jwe = $jws->encrypt($publicKey, 'RSA1_5', 'A128CBC-HS256');

        return $jwe->toString();
    }
}
