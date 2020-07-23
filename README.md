The API Wrapper can be used to quickly load data from the GameserverApp.com API.
You can connect with the API using your `Client ID` and `Client Secret`, which can be found on your API page: [https://dash.gameserverapp.com/configure/api](https://dash.gameserverapp.com/configure/api).

The API is rate-limited, meaning you can only make so many calls per minute. If you make more calls, your API keys can be (temporarily) disabled. Please keep this in mind when designing your application. For example, save the data you fetch from the API to a database or cache the output for a certain time.

This API wrapper allows you to do anything that is available on the [Community website](https://github.com/GameserverApp/community-website). Certain features are not (yet) implemented. 

## Support

If you have any questions, please reach out to [support@gameserverapp.com](mailto:support@gameserverapp.com).

## INSTALL

__Via Composer__

``composer require gameserverapp/php-api-wrapper``

```php
require 'vendor/autoload.php';
$api = new GameserverApp\ApiWrapper\GSAClient('<api key>','<secret>');
```

__Without Composer__

As an alternative you can include the API wrapper in your current website. Please copy/paste the code in the file `` into your project. After that make sure to include the file in your code.

```php
include('path/to/GSAClient.php');
$api = new GSAClient('<api key>','<secret>');
```

## USAGE

Grab all the servers that have a website name, connected to your domain
```php
$api->servers();
```
Response example:
```json
[
  {
    "id":132056,
    "name":"RUST",
    "cluster_name":"Self-hosted cluster",
    "selfhosted":0,
    "p2p":0,
    "twitch_sub_only":false,
    "app_id":"252490",
    "game":{
      "name":"RUST",
      "icon":"http:\/\/dash.gameserverapp.com\/img\/games\/rust.png",
      "steam":{
        "client_id":"252490",
        "server_id":"258550"
      },
      "support":{
        "delivery":false,
        "level":false,
        "gender":false
      }
    }
  }
]
```

## AVAILABLE METHODS

### Domain
```php
$api->domainSettings();
 
$api->domainStat('hours-played');
```
domainStat options:
- hours-played (graph)
- online-players (graph)
- new-characters (graph)
- online-count-last-7-days (graph)
- hours-played-last-7-days (graph)
- new-players-last-7-days (graph)
- active-tribes (group objects)
- newbies (character objects)
- top-players (character objects)
- last-online (character object)

### Cluster
```php
$api->clusterStat('newbies');
```
clusterStat options:
- online-count-last-7-days (graph)
- hours-played-last-7-days (graph)
- new-players-last-7-days (graph)
- active-tribes (group objects)
- newbies (character objects)
- top-players (character objects)
- last-online (character object)

### Server
```php
$api->serverStat(132056, 'top-players');

$api->servers();
```
serverStat options:
- online-count-last-7-days (graph)
- hours-played-last-7-days (graph)
- new-players-last-7-days (graph)
- active-tribes (group objects)
- newbies (character objects)
- top-players (character objects)
- last-online (character object)

### Group
```php
$api->group('00c666f6-d521-47ce-88f9-6ca36451bda0');
$api->groupStat('00c666f6-d521-47ce-88f9-6ca36451bda0', 'hours-played');

$api->groups();
```
groupStat options:
- hours-played
- levels-gained
- xp-gained

### User
```php
$api->user('6c4de76f-3df4-4b75-85a0-b4557c3f3564');
$api->userStat('6c4de76f-3df4-4b75-85a0-b4557c3f3564', 'levels-gained');

$api->users();
```
userStat options:
- hours-played
- levels-gained
- xp-gained

### Character
```php
$api->character('d1f6e8d1-0265-43ad-947e-c6bebe3343ee');
$api->characterStat('d1f6e8d1-0265-43ad-947e-c6bebe3343ee', 'xp-gained');

$api->characters();
$api->topCharacters();
$api->freshCharacters();
$api->onlineCharacters();
$api->spotlightCharacters();
```
characterStat options:
- hours-played
- levels-gained
- xp-gained
