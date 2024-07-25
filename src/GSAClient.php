<?php

namespace GameserverApp\ApiWrapper;

/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2019 GameserverApp.com / Max Vaessen
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class GSAClientException extends \ErrorException {};

class GSAClient
{
    const OAUTH_COOKIE_NAME = 'GSA_AUTH';

    private $key;
    private $secret;

    protected $url;
    protected $curl;

    ////////// DOMAIN ENDPOINTS //////////

    public function domainSettings()
    {
        return $this->request('get', 'v1','domain/settings');
    }

    /*
     * Stat options:
     * - hours-played (graph)
     * - online-players (graph)
     * - new-characters (graph)
     * - online-count-last-7-days (graph)
     * - hours-played-last-7-days (graph)
     * - new-players-last-7-days (graph)
     *
     * - active-tribes (group objects)
     * - newbies (character objects)
     * - top-players (character objects)
     * - last-online (character object)
     */
    public function domainStat($type = 'hours-played')
    {
        return $this->request('get', 'v1','domain/stats/' . $type);
    }

    ////////// CLUSTER ENDPOINTS //////////

    /*
     * Stat options:
     * - online-count-last-7-days (graph)
     * - hours-played-last-7-days (graph)
     * - new-players-last-7-days (graph)
     *
     * - active-tribes (group objects)
     * - newbies (character objects)
     * - top-players (character objects)
     * - last-online (character objects)
     */
    public function clusterStat($uuid, $type = 'online-count-last-7-days')
    {
        return $this->request('get', 'v1','cluster/' . $uuid . '/stats/' . $type);
    }

    ////////// SERVER ENDPOINTS //////////

    /*
     * Stat options:
     * - online-count-last-7-days (graph)
     * - hours-played-last-7-days (graph)
     * - new-players-last-7-days (graph)
     *
     * - active-tribes (group objects)
     * - newbies (character objects)
     * - top-players (character objects)
     * - last-online (character objects)
     */
    public function serverStat($id, $type = 'online-count-last-7-days')
    {
        return $this->request('get', 'v1','server/' . $id);
    }

    public function servers()
    {
        return $this->request('get', 'v1','servers');
    }

    ////////// GROUP ENDPOINTS //////////


    public function group($uuid)
    {
        return $this->request('get', 'v1','group/' . $uuid);
    }

    /*
     * Stat options:
     * - hours-played
     * - levels-gained
     * - xp-gained
     */
    public function groupStat($uuid, $type = 'hours-played')
    {

        return $this->request('get', 'v1','group/' . $type);
    }

    //requires Authorisation header -> OAuth login
    public function groupLog($uuid)
    {
        return $this->request('get', 'v1','group/' . $uuid . '/log');
    }

    //requires Authorisation header -> OAuth login
    public function groupSettings($uuid, $motd, $about)
    {
        return $this->request('post', 'v1','group/' . $uuid, [
            'motd' => $motd,
            'about' => $about
        ]);
    }

    public function groups()
    {
        return $this->request('get', 'v1','group');
    }

    ////////// USER ENDPOINTS //////////

    public function user($uuid)
    {
        return $this->request('get', 'v1','user/' . $uuid);
    }

    /*
     * Stat options:
     * - hours-played
     * - levels-gained
     * - xp-gained
     */
    public function userStat($uuid, $type = 'hours-played')
    {
        return $this->request('get', 'v1','user/' . $uuid . '/stats/' . $type);
    }

    public function users()
    {
        return $this->request('get', 'v1','user');
    }

    ////////// CHARACTER ENDPOINTS //////////

    public function character($uuid)
    {
        return $this->request('get', 'v1','character/' . $uuid);
    }

    /*
     * Stat options:
     * - hours-played
     * - levels-gained
     * - xp-gained
     */
    public function characterStat($uuid, $type = 'hours-played')
    {
        return $this->request('get', 'v1','character/' . $type);
    }

    public function characters()
    {
        return $this->request('get', 'v1','characters');
    }

    public function topCharacters()
    {
        return $this->request('get', 'v1','characters/top');
    }

    public function freshCharacters()
    {
        return $this->request('get', 'v1','characters/fresh');
    }

    public function onlineCharacters()
    {
        return $this->request('get', 'v1','characters/online');
    }

    public function spotlightCharacters()
    {
        return $this->request('get', 'v1','characters/spotlight');
    }

    public function __construct(
        $key,
        $secret,
        $url = 'https://api.gameserverapp.com/api',
        $sslverify = true
    ) {
        $this->key = $key;
        $this->secret = $secret;
        $this->url = $url;
        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_SSL_VERIFYPEER => $sslverify,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT      => 'GSA PHP API wrapper',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 20
        ));
    }

    private function request(
        $method,
        $version,
        $url,
        array $request = array()
    ) {
        $query = http_build_query($request, '', '&');

        //determin & set request path
        $path = $this->url . '/' . $version . '/' . $url;

        //set domain header
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'X-AUTH-GSA-CLIENT-ID: ' . $this->key,
        ));

        if(isset($_COOKIE[self::OAUTH_COOKIE_NAME])) {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
                'Authorisation: Bearer' . $_COOKIE[self::OAUTH_COOKIE_NAME],
            ));
        }

        if ($query) {
            $path .= '?' . $query;
        }

        curl_setopt($this->curl, CURLOPT_URL, $path);

        //set method
        switch ($method) {
            case 'POST':
            case 'post':
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $request);
                curl_setopt($this->curl, CURLOPT_POST, true);
                break;

            case 'DELETE':
            case 'delete':
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;

            case 'GET':
            case 'get':
                break;

            default:
                throw new GSAClientException('Unsupported method');
        }

        //execute request
        $result = curl_exec($this->curl);
        $httpcode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        if ($result === false) {
            throw new GSAClientException('CURL error: ' . curl_error($this->curl), $httpcode);
        }

        if ($httpcode != 200) {
            throw new GSAClientException($result, $httpcode);
        }

        return $result;
    }
}
