<?php
namespace Piesync\Partner;

use Jose\Factory\JWKFactory;
use Jose\Factory\JWEFactory;
use Jose\Factory\JWSFactory;

class TokenGenerator
{

    protected $payload;
    protected $privateKeyFile;
    protected $piesyncPublicKeyFile;


    public function setPayload(Payload $payload)
    {
        $this->payload = $payload->toArray();
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
        
        $privateJWK = JWKFactory::createFromKeyFile($this->privateKeyFile);
        $jws = JWSFactory::createJWSToCompactJSON($this->payload, $privateJWK, ['alg' => 'RS256']);

        $publicJWK = JWKFactory::createFromKey($this->piesyncPublicKeyFile, null, ['alg' => 'RSA1_5']);
        $jwe = JWEFactory::createJWEToCompactJSON($jws, $publicJWK, [
            'alg' => 'RSA1_5', 'enc' => 'A128CBC-HS256'
        ]);

        return $jwe;
    }
}
