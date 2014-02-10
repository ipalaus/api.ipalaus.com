<?php

namespace Isern\Core;

use Carbon\Carbon;

abstract class Entity
{

    /**
     * Indicates if the entity should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The entity's attributes.
     *
     * @var array
     */
    protected $attributes = array();

    /**
     * The entity attribute's original state.
     *
     * @var array
     */
    protected $original = array();

    /**
     * The accessors to append to the entity's array form.
     *
     * @var array
     */
    protected $appends = array();

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array();

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = array('*');

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = array();

    /**
     * Format for the stored dates.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Indicates if the entity exists.
     *
     * @var bool
     */
    public $exists = false;

    /**
     * Indicates if all mass assignment is enabled.
     *
     * @var bool
     */
    protected static $unguarded = false;

    /**
     * The cache of the mutated attributes for each class.
     *
     * @var array
     */
    protected static $mutatorCache = array();

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created_at';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'updated_at';

    /**
     * Create a new Entity instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = array(), $exists = false)
    {
        $this->exists = $exists;

        if ( ! empty($attributes)) {
            $this->fill($attributes);
        }
    }

    /**
     * Fill the entity with an array of attributes.
     *
     * @param  array  $attributes
     * @return \Isern\Core\Entity
     *
     * @throws \Isern\Core\MassAssignmentException
     */
    public function fill(array $attributes)
    {
        foreach ($this->fillableFromArray($attributes) as $key => $value)
        {
            // The developers may choose to place some attributes in the "fillable"
            // array, which means only those attributes may be set through mass
            // assignment to the entity, and all others will just be ignored.
            if ($this->isFillable($key))
            {
                $this->setAttribute($key, $value);
            }
            elseif ($this->totallyGuarded())
            {
                throw new MassAssignmentException($key);
            }
        }

        if ( ! $this->exists) {
            // If the entity doesn't exists and we have setup to auto-mantain a
            // timestamped entity, we will touch it here.
            if ($this->timestamps)
            {
                $this->updateTimestamps();
            }
        }


        return $this;
    }

    /**
     * Get the fillable attributes of a given array.
     *
     * @param  array  $attributes
     * @return array
     */
    protected function fillableFromArray(array $attributes)
    {
        if (count($this->fillable) > 0 && ! static::$unguarded)
        {
            return array_intersect_key($attributes, array_flip($this->fillable));
        }

        return $attributes;
    }

    /**
     * Get the fillable attributes for the entity.
     *
     * @return array
     */
    public function getFillable()
    {
        return $this->fillable;
    }

    /**
     * Set the fillable attributes for the entity.
     *
     * @param  array  $fillable
     * @return \Isern\Core\Entity
     */
    public function fillable(array $fillable)
    {
        $this->fillable = $fillable;

        return $this;
    }

    /**
     * Set the guarded attributes for the entity.
     *
     * @param  array  $guarded
     * @return \Isern\Core\Entity
     */
    public function guard(array $guarded)
    {
        $this->guarded = $guarded;

        return $this;
    }

    /**
     * Disable all mass assignable restrictions.
     *
     * @return void
     */
    public static function unguard()
    {
        static::$unguarded = true;
    }

    /**
     * Enable the mass assignment restrictions.
     *
     * @return void
     */
    public static function reguard()
    {
        static::$unguarded = false;
    }

    /**
     * Set "unguard" to a given state.
     *
     * @param  bool  $state
     * @return void
     */
    public static function setUnguardState($state)
    {
        static::$unguarded = $state;
    }

    /**
     * Determine if the given attribute may be mass assigned.
     *
     * @param  string  $key
     * @return bool
     */
    public function isFillable($key)
    {
        if (static::$unguarded) return true;

        // If the key is in the "fillable" array, we can of course assume that it's
        // a fillable attribute. Otherwise, we will check the guarded array when
        // we need to determine if the attribute is black-listed on the entity.
        if (in_array($key, $this->fillable)) return true;

        if ($this->isGuarded($key)) return false;

        return empty($this->fillable) && ! starts_with($key, '_');
    }

    /**
     * Determine if the given key is guarded.
     *
     * @param  string  $key
     * @return bool
     */
    public function isGuarded($key)
    {
        return in_array($key, $this->guarded) || $this->guarded == array('*');
    }

    /**
     * Determine if the entity is totally guarded.
     *
     * @return bool
     */
    public function totallyGuarded()
    {
        return count($this->fillable) == 0 && $this->guarded == array('*');
    }

    /**
     * Update the creation and update timestamps.
     *
     * @return void
     */
    protected function updateTimestamps()
    {
        $time = $this->freshTimestamp();

        if ( ! $this->isDirty(static::UPDATED_AT))
        {
            $this->setUpdatedAt($time);
        }

        if ( ! $this->exists && ! $this->isDirty(static::CREATED_AT))
        {
            $this->setCreatedAt($time);
        }
    }

    /**
     * Get a fresh timestamp for the entity.
     *
     * @return \Carbon\Carbon
     */
    public function freshTimestamp()
    {
        return new Carbon;
    }

    /**
     * Set the value of the "created at" attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setCreatedAt($value)
    {
        $this->{static::CREATED_AT} = $value;
    }

    /**
     * Set the value of the "updated at" attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setUpdatedAt($value)
    {
        $this->{static::UPDATED_AT} = $value;
    }

    /**
     * Get the name of the "created at" column.
     *
     * @return string
     */
    public function getCreatedAtColumn()
    {
        return static::CREATED_AT;
    }

    /**
     * Get the name of the "updated at" column.
     *
     * @return string
     */
    public function getUpdatedAtColumn()
    {
        return static::UPDATED_AT;
    }

    /**
     * Convert the entity instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributesToArray();
    }

    /**
     * Convert the entity's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = $this->attributes;

        // We want to spin through all the mutated attributes for this entity and call
        // the mutator for the attribute. We cache off every mutated attributes so
        // we don't have to constantly check on attributes that actually change.
        foreach ($this->getMutatedAttributes() as $key)
        {
            if ( ! array_key_exists($key, $attributes)) continue;

            $attributes[$key] = $this->mutateAttribute(
                $key, $attributes[$key]
            );
        }

        // All the keys defined as date will be foreced to be a string.
        foreach ($this->getDates() as $key) {
            if (isset($attributes[$key])) {
                $attributes[$key] = (string) $attributes[$key];
            }
        }

        // Here we will grab all of the appended, calculated attributes to this entity
        // as these attributes are not really in the attributes array, but are run
        // when we need to array or JSON the entity for convenience to the coder.
        foreach ($this->appends as $key)
        {
            $attributes[$key] = $this->mutateAttribute($key, null);
        }

        return $attributes;
    }

    /**
     * Get an attribute from the entity.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        $inAttributes = array_key_exists($key, $this->attributes);

        // If the key references an attribute, we can just go ahead and return the
        // plain attribute value from the entity. This allows every attribute to
        // be dynamically accessed through the _get method without accessors.
        if ($inAttributes || $this->hasGetMutator($key))
        {
            return $this->getAttributeValue($key);
        }
    }

    /**
     * Get a plain attribute (not a relationship).
     *
     * @param  string  $key
     * @return mixed
     */
    protected function getAttributeValue($key)
    {
        $value = $this->getAttributeFromArray($key);

        // If the attribute has a get mutator, we will call that then return what
        // it returns as the value, which is useful for transforming values on
        // retrieval from the entity to a form that is more useful for usage.
        if ($this->hasGetMutator($key))
        {
            return $this->mutateAttribute($key, $value);
        }

        // If the attribute is listed as a date, we will convert it to a DateTime
        // instance on retrieval, which makes it quite convenient to work with
        // date fields without having to create a mutator for each property.
        elseif (in_array($key, $this->getDates()))
        {
            if ($value) return $this->asDateTime($value);
        }

        return $value;
    }

    /**
     * Get an attribute from the $attributes array.
     *
     * @param  string  $key
     * @return mixed
     */
    protected function getAttributeFromArray($key)
    {
        if (array_key_exists($key, $this->attributes))
        {
            return $this->attributes[$key];
        }
    }

    /**
     * Sync the original attributes with the current.
     *
     * @return \Isern\Core\Entity
     */
    public function syncOriginal()
    {
        $this->original = $this->attributes;

        return $this;
    }

    /**
     * Determine if a given attribute is dirty.
     *
     * @param  string  $attribute
     * @return bool
     */
    public function isDirty($attribute)
    {
        return array_key_exists($attribute, $this->getDirty());
    }

    /**
     * Get the attributes that have been changed since last sync.
     *
     * @return array
     */
    public function getDirty()
    {
        $dirty = array();

        foreach ($this->attributes as $key => $value)
        {
            if ( ! array_key_exists($key, $this->original) || $value !== $this->original[$key])
            {
                $dirty[$key] = $value;
            }
        }

        return $dirty;
    }

    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasGetMutator($key)
    {
        return method_exists($this, 'get'.studly_case($key).'Attribute');
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return mixed
     */
    protected function mutateAttribute($key, $value)
    {
        return $this->{'get'.studly_case($key).'Attribute'}($value);
    }

    /**
     * Set a given attribute on the entity.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function setAttribute($key, $value)
    {
        // First we will check for the presence of a mutator for the set operation
        // which simply lets the developers tweak the attribute as it is set on
        // the entity, such as "json_encoding" an listing of data for storage.
        if ($this->hasSetMutator($key))
        {
            $method = 'set'.studly_case($key).'Attribute';

            return $this->{$method}($value);
        }

        $this->attributes[$key] = $value;
    }

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasSetMutator($key)
    {
        return method_exists($this, 'set'.studly_case($key).'Attribute');
    }

    /**
     * Get the attributes that should be converted to dates.
     *
     * @return array
     */
    public function getDates()
    {
        $defaults = array(static::CREATED_AT, static::UPDATED_AT);

        return array_merge($this->dates, $defaults);
    }

    /**
     * Return a timestamp as DateTime object.
     *
     * @param  mixed  $value
     * @return \Carbon\Carbon
     */
    protected function asDateTime($value)
    {
        // If this value is an integer, we will assume it is a UNIX timestamp's value
        // and format a Carbon object from this timestamp. This allows flexibility
        // when defining your date fields as they might be UNIX timestamps here.
        if (is_numeric($value))
        {
            return Carbon::createFromTimestamp($value);
        }

        // If the value is in simply year, month, day format, we will instantiate the
        // Carbon instances from that format. Again, this provides for simple date
        // fields on the database, while still supporting Carbonized conversion.
        elseif (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value))
        {
            return Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        }

        // Finally, we will just assume this date is in the format used by default on
        // the database connection and use that format to create the Carbon object
        // that is returned back out to the developers after we convert it here.
        elseif ( ! $value instanceof DateTime)
        {
            return Carbon::createFromFormat($this->dateFormat, $value);
        }

        return Carbon::instance($value);
    }

    /**
     * Get the mutated attributes for a given instance.
     *
     * @return array
     */
    public function getMutatedAttributes()
    {
        $class = get_class($this);

        if (isset(static::$mutatorCache[$class]))
        {
            return static::$mutatorCache[get_class($this)];
        }

        return array();
    }

    /**
     * Get all of the current attributes on the model.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set the array of model attributes. No checking is done.
     *
     * @param  array  $attributes
     * @param  bool   $sync
     * @return void
     */
    public function setRawAttributes($attributes, $sync = true)
    {
        if ($attributes instanceof Eloquent) {
            $attributes = $attributes->toArray();
        }

        $this->attributes = $attributes;

        if ($sync) {
            $this->syncOriginal();
        }

        return $this;
    }

    /**
     * Retrive if the entity exists.
     *
     * @return boolean
     */
    public function getExists()
    {
        return $this->exists;
    }

    /**
     * Set if the entity exists.
     *
     * @param  boolean  $value
     * @return void
     */
    public function setExists($value)
    {
        $this->exists = $value;
    }

    /**
     * Dynamically retrieve attributes on the entity.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the entity.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

}
