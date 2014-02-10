<?php

namespace Isern\Core;

use Illuminate\Database\Eloquent\Model;

abstract class Eloquent extends Model
{

    /**
     * Indicates if all mass assignment is enabled.
     *
     * @var bool
     */
    protected static $unguarded = true;

        /**
     * Create a new Eloquent model instance.
     *
     * @param  mixed  $attributes
     * @return void
     */
    public function __construct($attributes = array())
    {
        if ($attributes instanceof Entity) {
            $attributes = $attributes->toArray();
        }

        parent::__construct($attributes);
    }

}
