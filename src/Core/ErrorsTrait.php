<?php

namespace Isern\Core;

trait ErrorsTrait
{

    /**
     * Error type.
     *
     * @var string
     */
    protected $errorType = 'undefined';

    /**
     * Erorrs message bag.
     *
     * @var mixed
     */
    protected $errorsBag;

    /**
     * Get an array with the type and bag of message for the current error.
     *
     * @return array
     */
    public function getErrors()
    {
        return ['type' => $this->errorType, 'bag' => $this->errorsBag];
    }

    /**
     * Set the current error type and a messages bag if any.
     *
     * @param  string $type
     * @param  mixed  $bag
     *
     * @return void
     */
    public function setErrors($type, $bag = null)
    {
        $this->errorType = $type;
        $this->errorsBag = $bag;
    }

}
