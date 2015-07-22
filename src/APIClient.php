<?php

namespace Connect;

use Httpful\Mime;
use Httpful\Request;

class APIClient {

    /**
     * The base url for the connect api
     * @const string
     */
    const BASE_API_URL = 'https://api.getconnect.io/events/';

    /**
     * The project ID for this client
     * @var string
     */
    private $projectId;

    /**
     * The API key to use. Must be a push API key
     * @var string
     */
    private $apiKey;

    /**
     * Instantiates a new api Client instance.
     * @param string $projectId
     * @param string $apiKey
     */
    public function __construct($projectId, $apiKey) {
        $this->projectId = $projectId;
        $this->apiKey = $apiKey;
    }

    /**
     * Push an event to the Connect API.
     * @param string $collectionName The name of the collection to push to
     * @param array $event The event details
     * @return Response
     */
    public function pushEvent($collectionName, $event) {
        $url = self::BASE_API_URL . $collectionName;
        $result = Request::post($url)
            ->sendsAndExpectsType(Mime::JSON)
            ->addHeaders([
                'X-Project-Id' => $this->projectId,
                'X-Api-Key' => $this->apiKey
            ])
            ->body(json_encode($event))
            ->send();
        return $this->buildResponse($event, $result);
    }

    /**
     * Push an event batch to the Connect API.
     * @param array $eventBatch the batch to push
     * @return Response
     */
    public function pushEventBatch($eventBatch) {
        $url = self::BASE_API_URL;
        $result = Request::post($url)
            ->sendsAndExpectsType(Mime::JSON)
            ->addHeaders([
                'X-Project-Id' => $this->projectId,
                'X-Api-Key' => $this->apiKey
            ])
            ->body(json_encode($eventBatch))
            ->send();
        return $this->buildBatchResponse($eventBatch, $result);
    }

    /**
     * Build a single response to return to the user
     * @param array $event the event that the response is related to
     * @param \Httpful\Response $response The response from the API
     * @return Response
     */
    private function buildResponse($event, $response) {
        $success = !$response->hasErrors();
        $duplicate = ($response->code == 409);
        $statusCode = $response->code;
        $errorMessage = null;
        if ($response->code == 401) {
            $errorMessage = 'Unauthorised. Please check your Project Id and API Key';
        }
        if ($response->body != null && $response->body->errorMessage != null) {
            $errorMessage = $response->body->errorMessage;
        }
        return new Response($success, $duplicate, $statusCode, $errorMessage, $event);
    }

    /**
     * Build a batch of responses to return to the user
     * @param array $batch the batch that the response is related to
     * @param \Httpful\Response $response The response from the API
     * @return Response
     */
    private function buildBatchResponse($batch, $response) {
        $result = [];
        $responseBody = json_decode($response->raw_body, true);
        foreach ($batch as $collection => $events) {
            $eventResults = [];
            $eventResponses = $responseBody[$collection];
            foreach($events as $index => $event) {
                $eventResponse = $eventResponses[$index];
                $eventResult = new Response($eventResponse['success'], $eventResponse['duplicate'], null, $eventResponse['message'], $event);
                array_push($eventResults, $eventResult);
            }
            $result[$collection] = $eventResults;
        }

        $statusCode = $response->code;
        $errorMessage = $response->body->errorMessage;

        return new ResponseBatch($statusCode, $errorMessage, $result);
    }

}

