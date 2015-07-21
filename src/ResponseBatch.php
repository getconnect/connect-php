<?php

namespace Connect;


class ResponseBatch {

    /**
     * The HTTP status code of the response
     * @var int
     */
    private $statusCode;

    /**
     * The message describing errors of the request
     * @var string
     */
    private $errorMessage;

    /**
     * The responses for all events in the batch
     * @var array
     */
    private $responses;

    /**
     * Instantiates a new ResponseBatch object.
     * @param int $statusCode the HTTP status code returned by the API
     * @param string $errorMessage the error message if relevant
     * @param array $responses the responses for each event in the batch
     */
    public function __construct($statusCode, $errorMessage, $responses) {
        $this->statusCode = $statusCode;
        $this->errorMessage = $errorMessage;
        $this->responses = $responses;
    }

    /**
     * The status code returned by the Connect API. Note a 200 does not indicate that all events in the were successful
     * @return bool True if the request was successful, False otherwise
     */
    public function getStatusCode() {
        return $this->statusCode;
    }

    /**
     * The message associated with the batch request
     * @return string The message if an error occurred with the request, null otherwise.
     */
    public function getErrorMessage() {
        return $this->errorMessage;
    }

    /**
     * The responses for all the individual events in the batch
     * @return array The arrays of all the responses in the batch keyed by the collection name
     */
    public function getResponses() {
        return $this->responses;
    }

}