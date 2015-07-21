<?php

namespace Connect;

class Event {

    /**
     * The raw event details supplied
     * @var array
     */
    private $eventDetails;

    /**
     * Instantiates a new Connect event object.
     * @param array $eventDetails
     */
    public function __construct($eventDetails) {
        $this->eventDetails = $eventDetails;
        $this->validate();
        $this->process($this->eventDetails);
    }

    /**
     * Get the processed event as an associative array.
     * @return array the processed event details
     */
    public function getDetails() {
        return $this->eventDetails;
    }

    /**
     * Checks if an event is valid for Connect.
     * @throws InvalidPropertyNameException if the event contains invalid properties.
     */
    private function validate() {
        $errors = [];

        foreach ($this->eventDetails as $key => $value) {
            if (strpos($key, 'tp_') !== false) {
                $errors[$key] = 'Property names cannot start with the reserved prefix \'tp_\'';
            }
            if (strpos($key, '.') !== false) {
                $errors[$key] = 'Property names cannot contain a period (.)';
            }
        }

        if (!empty($errors)) {
            throw new InvalidPropertyNameException($errors);
        }
    }

    /**
     * Recursively converts any nested DateTime instances to ISO8601 strings
     * in preparation for serialization. Note, mutates the supplied array.
     * @param array Properties to convert.
     */
    private function process(&$details) {
        foreach ($details as &$value) {
            if (is_array($value)) {
                $this->process($value);
                continue;
            }
            if ($value instanceof \DateTime) {
                $value = $value->format(\DateTime::ISO8601);
                continue;
            }
        }
    }

}
