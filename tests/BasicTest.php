<?php
namespace GameserverApp\ApiWrapper\Tests;

use Dotenv\Dotenv;
use GameserverApp\ApiWrapper\GSAClient;
use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    protected $api;

    protected function setUp()
    {
        $dotenv = new Dotenv(__DIR__ . '/..');
        $dotenv->load();

        $this->api = new GSAClient(
            getenv('GSA_CLIENT_ID'),
            getenv('GSA_CLIENT_SECRET')
        );
    }

    public function testDomainSettings()
    {
        $result = $this->api->domainSettings();

        $this->assertTrue(isset($result->name));
    }

    public function testDomainStat()
    {
        $result = $this->api->domainStat();

        $this->assertTrue(isset($result->options));
    }

    public function testServers()
    {
        $result = $this->api->servers();


        die(var_dump($result));
        $this->assertTrue(count($result) > 0);
    }

    public function testGroups()
    {
        $result = $this->api->groups();

        $this->assertTrue(count($result) > 0);
    }

    public function testUsers()
    {
        $result = $this->api->users();

        $this->assertTrue(count($result) > 0);
    }

    public function testCharacters()
    {
        $result = $this->api->characters();

        $this->assertTrue(count($result) > 0);
    }

    public function testTopCharacters()
    {
        $result = $this->api->topCharacters();

        $this->assertTrue(isJson($result));
    }

    public function testFreshCharacters()
    {
        $result = $this->api->freshCharacters();

        $this->assertTrue(isJson($result));
    }

    public function testOnlineCharacters()
    {
        $result = $this->api->onlineCharacters();

        $this->assertTrue(isJson($result));
    }

    public function testSpotlightCharacters()
    {
        $result = $this->api->spotlightCharacters();

        $this->assertTrue(isJson($result));
    }

}

function isJson($string)
{
    json_decode($string);

    return (json_last_error() == JSON_ERROR_NONE);
}