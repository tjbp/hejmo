<?php

/*
|--------------------------------------------------------------------------
| Custom validation callbacks
|--------------------------------------------------------------------------
|
| Below are registered all extra validation callbacks that don't otherwise
| come bundled with Laravel.
|
*/

// Checks a strtotime()-compatible string is in the future.
Validator::extend('future', function($attribute, $value, $parameters)
{
    return strtotime($value) > time();
});

// Checks a file containing JSON is valid.
Validator::extend('jsonFile', function($attribute, $value, $parameters)
{
    return !is_null(json_decode(file_get_contents($value->getRealPath())));
});
