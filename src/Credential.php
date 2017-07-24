<?php

namespace Piesync\Partner;
use JsonSerializable;
use Serializable;
use Illuminate\Contracts\Support\Arrayable;

class Credential implements JsonSerializable, Arrayable, Serializable
{
    public $publicPemFile;
    public $privatePemFile;
    public $piesyncPublicPemFile;

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function serialize() {
        return json_encode($this->toArray());
    }

    public function unserialize($data) {
        $unserialized = json_decode($data, true);
        $this->publicPemFile = $unserialized['public_pem_file'];
        $this->privatePemFile = $unserialized['private_pem_file'];
        $this->piesyncPublicPemFile = $unserialized['piesync_public_pem_file'];
    }

    public function toArray()
    {
        return [
            'public_pem_file' => $this->publicPemFile,
            'private_pem_file' => $this->privatePemFile,
            'piesync_public_pem_file' => $this->piesyncPublicPemFile
        ];
    }

}
