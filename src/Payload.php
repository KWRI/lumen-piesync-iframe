<?php

namespace Piesync\Partner;

use Illuminate\Contracts\Support\Arrayable;

class Payload implements Arrayable
{

    private $partner;
    private $app;
    private $teamId;
    private $userId;
    private $email;
    private $apiAuth;
    private $exp;

    public function setPartner($partner)
    {
        $this->partner = $partner;
        return $this;
    }

    public function setApp($app)
    {
        $this->app = $app;
        return $this;
    }

    public function setTeamId($teamId)
    {
        $this->teamId = $teamId;
        return $this;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }


    public function setApiAuth($apiAuth)
    {
        $this->apiAuth = $apiAuth;
        return $this;
    }

    public function setExpiration($expiration)
    {
        $this->exp = $expiration;
        return $this;
    }

    public function getPartner()
    {
        return $this->partner;
    }

    public function getApp()
    {
        return $this->app;
    }

    public function getTeamId()
    {
        return $this->teamId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getEmail()
    {
        return $this->email;
    }


    public function getApiAuth()
    {
        return $this->apiAuth;
    }

    public function getExpiration()
    {
        return $this->expiration;
    }

    public function toArray()
    {
        return [
            'partner' => $this->partner,
            'app' => $this->app,
            'team_id' => $this->teamId,
            'user_id' => $this->userId,
            'email' => $this->email,
            'api_auth' => $this->apiAuth,
            'exp' => $this->exp,
        ];
    }
}
