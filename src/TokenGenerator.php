<?php
namespace Piesync\Partner;


use Jose\Factory\JWEFactory;
use Jose\Factory\JWSFactory;
use Jose\Factory\JWKFactory;
use Jose\Algorithm\Signature\RS256;
use Jose\Signer;
use Jose\Object\JWS;
use Jose\Encrypter;


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

        $privateJWK = JWKFactory::createFromKeyFile($this->privateKeyFile, null);
        $jws = JWSFactory::createJWSToCompactJSON($this->payload, $privateJWK, ['alg' => 'RS256']);

        $publicKey = JWKFactory::createFromKeyFile($this->piesyncPublicKeyFile, null, ['alg' => 'RSA1_5']);
        $jwe = JWEFactory::createJWEToCompactJSON($jws, $publicKey, ['alg' => 'RSA1_5', 'enc' =>
'A128CBC-HS256']);

        return $jwe;

    }
}
