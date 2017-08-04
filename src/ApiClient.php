<?php

namespace Piesync\Partner;

use GuzzleHttp\ClientInterface as GuzzleHttp;
use Illuminate\Support\Collection;

class ApiClient
{
    const ENDPOINT = 'https://partner.piesync.com/v1/';

    public function __construct(GuzzleHttp $httpClient)
    {
        $this->client = $httpClient;
    }

    /**
     * This endpoint will return the status of the user's account,
     * and all connections created through the API as well as a count
     * of how many connections are in each state ( pending , active or paused ).
     * You will not be able to check the status of connections created outside
     * of the partner flow, e.g. if the user already has or upgrades to a normal
     * PieSync account and adds other connections in PieSync itself.
     *
     * @param string $token    Partner token
     * @return \Illuminate\Support\Collection
     */
    public function connections($token)
    {
        return $this->get('connections', $token);
    }

    /**
     * This endpoint allows you to fetch administrative reports
     *  on the connections that were created through the partner flow.
     * @param string $token    Partner token
     * @return \Illuminate\Support\Collection
     */
    public function connectionsReport($token)
    {
        return $this->get('connections/report', $token);
    }

    /**
     * This endpoint allows you to pause connection that was created through the API
     * @param string $token    Partner token
     * @return \Illuminate\Support\Collection
     */
    public function pauseConnection($token)
    {
        return $this->post('connection/pause', $token);
    }

    /**
     * This endpoint allows you to resume connection that was created through the API
     * @param string $token    Partner token
     * @return \Illuminate\Support\Collection
     */
    public function resumeConnection($token)
    {
        return $this->post('connection/resume', $token);
    }

    /**
     * This endpoint allows you to trigger sync on connection
     * @param string $token    Partner token
     * @return \Illuminate\Support\Collection
     */
    public function triggerSync($token)
    {
        return $this->post('connection/trigger', $token);
    }

    public function deleteConnection($token)
    {
        return $this->delete('connection', $token);
    }

    /**
     * Send GET http request
     * @param string $token    Partner token
     * @return \Illuminate\Support\Collection
     */
    public function get($uri, $token)
    {
        return $this->request('GET', $uri, $token);
    }

    /**
     * Send POST http request
     * @param string $token    Partner token
     * @return \Illuminate\Support\Collection
     */
    public function post($uri, $token)
    {
        return $this->request('POST', $uri, $token);
    }

    /**
     * Send DELETE http request
     */
    public function delete($uri, $token)
    {
        return $this->request('DELETE', $uri, $token);
    }

    /**
     * Send HTTP Request
     * @param string $token    Partner token
     * @return \Illuminate\Support\Collection
     */
    public function request($verb, $uri, $token)
    {
        $token =  $this->client->request($verb, self::ENDPOINT . $uri, [
            'query' => ['token' => $token]
        ]);

        return new Collection(json_decode($token->getBody(), true));
    }

}
