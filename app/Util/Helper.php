<?php

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Formats the given date into Y-m-d format.
 *
 * @param string
 * @return string
 */
function formatDate($date)
{
    if ( !isset($date) ) {
        return null;
    }

    return Carbon::parse($date)->format('Y-m-d');
}

/**
 * Formats the given timestamp into H:i:s A format.
 *
 * @param string
 * @return string
 */
function formatTime($timestamp)
{
    if ( !isset($timestamp) ) {
        return null;
    }

    return Carbon::parse($timestamp)->format('H:i:s A');
}

/**
 * Formats the timestamp into a readable format.
 * e.g.
 * 1 hour ago
 *
 * @param string
 * @return string
 */
function timeSince($timestamp)
{
    if ( !isset($timestamp) ) {
        return null;
    }

    return Carbon::parse($timestamp)->diffForHumans();
}

/**
 * Cleans up the string from special chars.
 *
 * @return String
 */
function cleanTimeZoneName($string)
{
    if ( !isset($string) ) {
        return 'Not specified';
    }

    return str_replace('-', ' ', str_replace('_', ' ', str_replace('/', ', ', $string)));
}

/**
 * A listing of all timezones.
 *
 * @return Array
 */
function allTimeZones()
{
    return array_map(function ($timezone) {
        return [
            'name' => cleanTimeZoneName($timezone),
            'raw' => $timezone
        ];
    }, timezone_identifiers_list());
}

/**
 * Find the timezone by clean name.
 * Returns the original name.
 *
 * @param String $string e.g. 'America, New York'
 * @return String
 */
function findTimeZone($string)
{
    return collect(allTimeZones())->firstWhere('name', $string);
}

/**
 * Sets the timezone based on the authenticated user.
 *
 * @return String
 */
function setTimeZone($datetime)
{
    if ( !isset($datetime) ) {
        return null;
    }

    $timezone = Auth::check() ?
        Auth::user()->time_zone
        : config('app.timezone');

    return Carbon::createFromFormat('Y-m-d H:i:s', $datetime, $timezone);
}

// Converts a number into a short version, eg: 1000 -> 1k
// Based on: http://stackoverflow.com/a/4371114
// reference https://gist.github.com/RadGH/84edff0cc81e6326029c
function toShortNumberFormat( $n, $precision = 1 ) {
	if ($n < 900) {
		// 0 - 900
		$n_format = number_format($n, $precision);
		$suffix = '';
	} else if ($n < 900000) {
		// 0.9k-850k
		$n_format = number_format($n / 1000, $precision);
		$suffix = 'K';
	} else if ($n < 900000000) {
		// 0.9m-850m
		$n_format = number_format($n / 1000000, $precision);
		$suffix = 'M';
	} else if ($n < 900000000000) {
		// 0.9b-850b
		$n_format = number_format($n / 1000000000, $precision);
		$suffix = 'B';
	} else {
		// 0.9t+
		$n_format = number_format($n / 1000000000000, $precision);
		$suffix = 'T';
	}

  // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
  // Intentionally does not affect partials, eg "1.50" -> "1.50"
	if ( $precision > 0 ) {
		$dotzero = '.' . str_repeat( '0', $precision );
		$n_format = str_replace( $dotzero, '', $n_format );
	}

	return $n_format . $suffix;
}

/**
 * Checks if family is created today.
 *
 * @return bool
 */
function toMoneyFormat($attribute)
{
    return '$'.number_format($attribute, 2);
}

/**
 * Checks if the string is not empty.
 *
 * @param string
 * @return bool
 */
function isNotEmpty($string) {
    return Str::of($string)->trim()->isNotEmpty();
}

/**
 * Reference [https://stackoverflow.com/questions/13637145/split-text-string-into-first-and-last-name-in-php]
 *
 * @param mixed $name
 * @return (string|string[]|null)[]
 */
function splitName($name) {
    $name = trim($name);
    $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
    $first_name = trim( preg_replace('#'.preg_quote($last_name,'#').'#', '', $name ) );
    return array($first_name, $last_name);
}
