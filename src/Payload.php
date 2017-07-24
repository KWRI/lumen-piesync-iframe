<?php

namespace Piesync\Partner;

use JsonSerializable;
use Serializable;
use Illuminate\Contracts\Support\Arrayable;

class Payload implements JsonSerializable, Arrayable, Serializable
{

    public $partner;
    public $app;
    public $team_id;
    public $user_id;
    public $email;
    public $api_auth = [];
    public $exp;


    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function serialize() {
        return json_encode($this->toArray());
    }

    public function unserialize($data) {
        $data  = json_decode($data, true);
        foreach ($data as $attributeName => $value) {
            $this->$attributeName = $value;
        }
    }

    public function toArray()
    {
        return [
            'partner' => $this->partner,
            'app' => $this->app,
            'team_id' => $this->team_id,
            'user_id' => $this->user_id,
            'email' => $this->email,
            'api_auth' => $this->api_auth,
            'exp' => $this->exp,
        ];
    }



}
