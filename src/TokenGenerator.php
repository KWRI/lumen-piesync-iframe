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

        $privateJWK = JWKFactory::createFromKeyFile($this->privateKeyFile);
        $jws = JWSFactory::createJWSToCompactJSON($this->payload, $privateJWK, ['alg' => 'RS256']);

        $encHeaders = ['alg' => 'RSA1_5', 'enc' => 'A128CBC-HS256'];
        $jwe = JWEFactory::createJWE($jws, $encHeaders);
        $ejwk = JWKFactory::createFromKeyFile($this->piesyncPublicKeyFile);
        $jwe = $jwe->addRecipientInformation($ejwk);
        $encrypter = Encrypter::createEncrypter(['RSA1_5'], ['A128CBC-HS256']);
        $encrypter->encrypt($jwe);

        return $jwe->toCompactJson(0);

    }
}
