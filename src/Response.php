<?php

namespace Connect;

class Response {

    /**
     * The success of the response
     * @var bool
     */
    private $success;

    /**
     * Was the vent a duplicate
     * @var bool
     */
    private $duplicate;

    /**
     * The HTTP status code of the response
     * @var int
     */
    private $statusCode;

    /**
     * The message describing any errors that occurred
     * @var string
     */
    private $errorMessage;

    /**
     * The event that the response relates to
     * @var array
     */
    private $event;

    /**
     * Instantiates a new Response object.
     * @param bool $success was the event successfully added to Connect
     * @param bool $duplicate was the event a duplicate
     * @param int $statusCode the HTTP status code returned by the API
     * @param string $errorMessage the error message if relevant
     * @param array $event the error message if relevant
     */
    public function __construct($success, $duplicate, $statusCode, $errorMessage, $event) {
        $this->success = $success;
        $this->duplicate = $duplicate;
        $this->statusCode = $statusCode;
        $this->errorMessage = $errorMessage;
        $this->event = $event;
    }

    /**
     * Was the event pushed to Connect successfully?
     * @return bool True if the request was successful, False otherwise
     */
    public function wasSuccessful() {
        return $this->success;
    }

    /**
     * Has an event with the same id already been pushed to this collection
     * @return bool True if the event is a duplicate, False otherwise
     */
    public function isDuplicate() {
        return $this->duplicate;
    }

    /**
     * The status code returned by the Connect API
     * @return bool True if the request was successful, False otherwise
     */
    public function getStatusCode() {
        return $this->statusCode;
    }

    /**
     * The message associated with any errors response
     * @return string The message if an error occurred, null otherwise
     */
    public function getErrorMessage() {
        return $this->errorMessage;
    }

    /**
     * Get the event that this response relates too
     * @return array The processed event
     */
    public function getEvent() {
        return $this->event;
    }

}