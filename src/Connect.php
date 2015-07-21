<?php

namespace Connect;

class Connect {

    /**
     * An instance of the Connect Client
     * @var Client
     */
    private static $client;

    /**
     * Initializes the Connect client singleton instance
     * @param $projectId
     * @param $apiKey
     * @return Client
     */
    public static function initialize($projectId, $apiKey) {
        if(!isset(self::$client)) {
            self::$client = new Client($projectId, $apiKey);
        }
        return self::$client;
    }

    /**
     * Returns a singleton instance of Connect
     * @return Client
     */
    public static function getClient() {
        return self::$client;
    }

    /**
     * Pushes an event to Connect.
     * @param string|array $collectionNameOrBatch The name of the collection you are pushing to or alternatively a batch of events.
     * Batches should be an associative array containing arrays of events keyed by collection name.
     * @param array $eventOrEvents Either a single event as an associative array, or an array of events as associative arrays
     * @return Response|array Either a response for single events or an Array of Responses for batch events
     */
    public static function push($collectionNameOrBatch, $eventOrEvents = null) {
        if(!isset(self::$client)) {
            throw new ConfigurationException('You must call initialize first with a Project Id and a Push Key');
        }
        return self::$client->push($collectionNameOrBatch, $eventOrEvents);
    }
}

