<?php

/**
 * Return a relative string for a date.
 *
 * @param integer $timestamp
 * @return string
 */
function relative_date($timestamp)
{
    return \App::make('date')->setTimestamp($timestamp)->getRelativeDate();
}

/**
 * Return a relative string for a timespan.
 *
 * @param integer $seconds
 * @return string
 */
function relative_timespan($seconds)
{
    // Just return seconds if less than a minute.
    if ($seconds < 60) {
        return number_format($seconds) . " seconds";
    }

    // Most values will be a fixed number of units.
    if ($seconds % 31557600 == 0) {
        return ($seconds / 31557600) . " years";
    } elseif ($seconds % 2629739 == 0) {
        return number_format($seconds / 2629739) . " months";
    } elseif ($seconds % 604800 == 0) {
        return number_format($seconds / 604800) . " weeks";
    } elseif ($seconds % 86400 == 0) {
        return number_format($seconds / 86400) . " days";
    } elseif ($seconds % 3600 == 0) {
        return number_format($seconds / 3600) . " hours";
    } elseif ($seconds % 60 == 0) {
        return number_format($seconds / 60) . " minutes";
    }

    // Handle any fractional timespans by finding the largest unit and rounding
    // to two decimal points.
    if ($seconds < 3600) {
        return number_format($seconds / 60, 2) . " minutes";
    } elseif ($seconds < 86400) {
        return number_format($seconds / 3600, 2) . " hours";
    } elseif ($seconds < 604800) {
        return number_format($seconds / 86400, 2) . " days";
    } elseif ($seconds < 2629739) {
        return number_format($seconds / 604800, 2) . " weeks";
    } elseif ($seconds < 31557600) {
        return number_format($seconds / 2629739, 2) . " months";
    } else {
        return ($seconds / 31557600) . " years";
    }
}

/**
 * Return an array of strings as "string, string and string".
 *
 * @param array $words
 * @return string
 */
function punctuate_array($words)
{
    if (count($words) === 1) return $words[0];

    $last = array_pop($words);

    return implode(', ', $words) . ' and ' . $last;
}
