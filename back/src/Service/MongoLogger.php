<?php
// back/src/Service/MongoLogger.php
namespace App\Service;

use MongoDB\Client;

class MongoLogger
{
    private $client;
    private $collection;

    public function __construct(string $mongoUri = 'mongodb://mongo:27017', string $db = 'ecoride', string $collection = 'logs')
    {
        $this->client = new Client($mongoUri);
        $this->collection = $this->client->selectDatabase($db)->selectCollection($collection);
    }

    public function log(string $level, string $message, array $context = [])
    {
        $doc = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
            'created_at' => new \MongoDB\BSON\UTCDateTime()
        ];
        $this->collection->insertOne($doc);
    }
}
