<?php

namespace AlibabaCloud\Client;

use AlibabaCloud\Client\Exception\ClientException;
use Closure;
use League\CLImate\CLImate;
use Stringy\Stringy;

/*
|--------------------------------------------------------------------------
| Global Functions for Alibaba Cloud
|--------------------------------------------------------------------------
|
| Some common global functions are defined here.
| This file will be automatically loaded.
|
*/

/**
 * @param      $filename
 * @param bool $throwException
 *
 * @return bool
 * @throws ClientException
 */
function inOpenBasedir($filename, $throwException = false)
{
    $open_basedir = ini_get('open_basedir');
    if (!$open_basedir) {
        return true;
    }

    $dirs = explode(PATH_SEPARATOR, $open_basedir);
    if (!$dirs) {
        return true;
    }

    foreach ($dirs as $dir) {
        if (!Stringy::create($dir)->endsWith(DIRECTORY_SEPARATOR)) {
            $dir .= DIRECTORY_SEPARATOR;
        }
        if (false !== strpos($filename, $dir)) {
            return true;
        }
    }

    if ($throwException === false) {
        return false;
    }

    throw new ClientException('open_basedir restriction in effect. '
                              . "File($filename) is not within the allowed path(s): ($open_basedir)", 'SDK.InvalidPath');
}

/**
 * @return bool
 */
function isWindows()
{
    return PATH_SEPARATOR === ';';
}

/**
 * @return CLImate
 */
function cliMate()
{
    return new CLImate();
}

/**
 * @param string      $string
 * @param string|null $flank
 * @param string|null $char
 * @param int|null    $length
 *
 * @return void
 */
function backgroundRed($string, $flank = null, $char = null, $length = null)
{
    cliMate()->br();
    if ($flank !== null) {
        cliMate()->backgroundRed()->flank($flank, $char, $length);
        cliMate()->br();
    }
    cliMate()->backgroundRed($string);
    cliMate()->br();
}

/**
 * @param string      $string
 * @param string|null $flank
 * @param string|null $char
 * @param int|null    $length
 *
 * @return void
 */
function backgroundGreen($string, $flank = null, $char = null, $length = null)
{
    cliMate()->br();
    if ($flank !== null) {
        cliMate()->backgroundGreen()->flank($flank, $char, $length);
    }
    cliMate()->backgroundGreen($string);
    cliMate()->br();
}

/**
 * @param string      $string
 * @param string|null $flank
 * @param string|null $char
 * @param int|null    $length
 *
 * @return void
 */
function backgroundBlue($string, $flank = null, $char = null, $length = null)
{
    cliMate()->br();
    if ($flank !== null) {
        cliMate()->backgroundBlue()->flank($flank, $char, $length);
    }
    cliMate()->backgroundBlue($string);
    cliMate()->br();
}

/**
 * @param string      $string
 * @param string|null $flank
 * @param string|null $char
 * @param int|null    $length
 *
 * @return void
 */
function backgroundMagenta($string, $flank = null, $char = null, $length = null)
{
    cliMate()->br();
    if ($flank !== null) {
        cliMate()->backgroundMagenta()->flank($flank, $char, $length);
    }
    cliMate()->backgroundMagenta($string);
    cliMate()->br();
}

/**
 * @param array $array
 */
function json(array $array)
{
    cliMate()->br();
    cliMate()->backgroundGreen()->json($array);
    cliMate()->br();
}

/**
 * @param array $array
 *
 * @return void
 */
function redTable($array)
{
    /**
     * @noinspection PhpUndefinedMethodInspection
     */
    cliMate()->redTable($array);
}

/**
 * @param mixed  $result
 * @param string $title
 *
 * @return void
 */
function block($result, $title)
{
    cliMate()->backgroundGreen()->flank($title, '--', 20);
    dump($result);
}

/**
 * @param array $arrays
 *
 * @return array
 */
function arrayMerge(array $arrays)
{
    $result = [];
    foreach ($arrays as $array) {
        foreach ($array as $key => $value) {
            if (is_int($key)) {
                $result[] = $value;
                continue;
            }

            if (isset($result[$key]) && is_array($result[$key])) {
                $result[$key] = arrayMerge(
                    [$result[$key], $value]
                );
                continue;
            }

            $result[$key] = $value;
        }
    }

    return $result;
}

/**
 * Gets the value of an environment variable.
 *
 * @param  string $key
 * @param  mixed  $default
 *
 * @return mixed
 */
function env($key, $default = null)
{
    $value = getenv($key);

    if ($value === false) {
        return value($default);
    }

    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'empty':
        case '(empty)':
            return '';
        case 'null':
        case '(null)':
            return null;
    }

    if (envSubstr($value)) {
        return substr($value, 1, -1);
    }

    return $value;
}

/**
 * @param $value
 *
 * @return bool
 */
function envSubstr($value)
{
    return ($valueLength = strlen($value)) > 1 && strpos($value, '"') === 0 && $value[$valueLength - 1] === '"';
}

/**
 * Return the default value of the given value.
 *
 * @param  mixed $value
 *
 * @return mixed
 */
function value($value)
{
    return $value instanceof Closure ? $value() : $value;
}