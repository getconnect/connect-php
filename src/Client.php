<?php

namespace Connect;

class Client
{
    /**
     * An instance of the Connect API client
     * @var APIClient
     */
    private $client;

    /**
     * Instantiates a new Connect instance.
     * @param string $projectId
     * @param string $apiKey
     */
    public function __construct($projectId, $apiKey) {
        $this->client = new APIClient($projectId, $apiKey);
    }

    /**
     * Pushes an event to Connect.
     * @param string|array $collectionOrBatch Either a batch or the name of the collection you are pushing too.
     * @param array $eventOrEvents Either a single event as an associative array, or an array of events as associative arrays
     * @return Response|array Either a response for single events or an Array of Responses for batch events
     */
    public function push($collectionOrBatch, $eventOrEvents = null) {
        if (!is_string($collectionOrBatch)) {
            return $this->pushEventBatch($collectionOrBatch);
        } else {
            if ($this->is_assoc($eventOrEvents)) {
                return $this->pushEvent($collectionOrBatch, $eventOrEvents);
            } else {
                $batch = $this->buildBatchFromArray($collectionOrBatch, $eventOrEvents);
                return $this->pushEventBatch($batch);
            }
        }
    }

    /**
     * Push an event to the API client.
     * @param string $collection The name of the collection to push to
     * @param array $eventDetails The event details
     * @return Response
     */
    private function pushEvent($collection, $eventDetails) {
        $event = new Event($eventDetails);
        return $this->client->pushEvent($collection, $event->getDetails());
    }

    /**
     * Push an event batch to the Connect API.
     * @param array $batch the batch to push
     * @return Response
     */
    private function pushEventBatch($batch) {
        $eventBatch = [];
        foreach ($batch as $collection => $events) {
            $eventBatch[$collection] = array_map(function ($eventDetails) {
                $event = new Event($eventDetails);
                return $event->getDetails();
            }, $events);
        }
        return $this->client->pushEventBatch($eventBatch);
    }

    /**
     * Converts an array of events to the batch format.
     * @param string $collection The collection to push to.
     * @param array $events The array of events.
     * @return array events in the batch format.
     */
    private function buildBatchFromArray($collection, $events) {
        $batch[$collection] = $events;
        return batch;
    }

    /**
     * Checks if an array is associative.
     * @param array $array The array of events.
     * @return bool True if the array is associative false otherwise.
     */
    private function is_assoc($array) {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}
