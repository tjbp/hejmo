<?php

namespace Models;

class User extends \Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The database table's primary key.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * Disable automatic timestamp column population.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Array of properties protected from mass-assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Accessor to convert properties to snake_case, as in the database.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        return parent::__get(snake_case($property));
    }

    /**
     * Mutator to convert properties to snake_case, as in the database.
     *
     * @param string $property
     * @param mixed $value
     * @return void
     */
    public function __set($property, $value)
    {
        parent::__set(snake_case($property), $value);
    }
}
