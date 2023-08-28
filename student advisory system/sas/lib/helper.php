<?php

use App\Rules\ConfirmedRule;
use App\Rules\ExistsRule;
use App\Rules\StringRule;
use App\Rules\UniqueRule;
use Rakit\Validation\Validator;

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        dump($vars);
        exit();
    }
}

function base_url()
{
    return trim(
        sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            trim($_SERVER['SERVER_NAME']),
            isset($_SERVER['SERVER_PORT']) && !in_array($_SERVER['SERVER_PORT'], [80, 443]) ? ":{$_SERVER['SERVER_PORT']}" : "",
        )
    );
}

function echo_base_url()
{
    echo base_url();
}

function current_url()
{
    return trim(
        sprintf(
            "%s://%s%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            isset($_SERVER['SERVER_PORT']) && !in_array($_SERVER['SERVER_PORT'], [80, 443]) ? ":{$_SERVER['SERVER_PORT']}" : "",
            $_SERVER['REQUEST_URI'],
        )
    );
}

function echo_current_url()
{
    echo current_url();
}

function validate($data, $rules = [], $messages = [])
{
    static $validator = (new Validator());
    $validator->addValidator('unique', new UniqueRule());
    $validator->addValidator('string', new StringRule());
    $validator->addValidator('exists', new ExistsRule());
    $validator->addValidator('confirmed', new ConfirmedRule());

    $results = $validator->validate($data, $rules, $messages);

    return [
        'data' => $results->getValidatedData(),
        'errors' => $results->errors()->firstOfAll()
    ];
}

function old($key)
{
    echo $_POST[$key] ?? '';
}

function oldGet($key)
{
    echo $_GET[$key] ?? '';
}

function redirect($url)
{
    header("location: {$url}");
    exit;
}

function dashboard()
{
    my_session_start();

    if (!isset($_SESSION['role'])) {
        return base_url();
    }

    return sprintf("%s/views/%s/dashboard.php", base_url(), $_SESSION['role']);
}

function echo_dashboard()
{
    echo dashboard();
}

function my_session_start($force = false)
{
    static $initialized = false;

    if (isset($_SESSION['user'])) {
        return;
    }

    if (!$initialized || $force) {
        session_start();
        $initialized = true;
    }
}

function has_session()
{
    my_session_start();

    return isset($_SESSION['user']) && isset($_SESSION['role']);
}

function echo_is_invalid($errors, $key)
{
    if (!has_error($errors, $key)) {
        return;
    }

    echo 'is-invalid';
}

function echo_error($errors, $key)
{
    if (!has_error($errors, $key)) {
        return;
    }

    echo '
    <div class="invalid-feedback">
        ' . $errors[$key] . '
    </div>
    ';
}

function has_error($errors, $key)
{
    return isset($errors[$key]);
}

/** 
 * @see https://phppot.com/php/php-time-ago-function 
 */
function timeago($date)
{
    $timestamp = strtotime($date);

    $strTime = array("second", "minute", "hour", "day", "month", "year");
    $length = array("60", "60", "24", "30", "12", "10");

    $currentTime = time();
    if ($currentTime >= $timestamp) {
        $diff     = time() - $timestamp;
        for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
            $diff = $diff / $length[$i];
        }

        $diff = round($diff);
        return $diff . " " . $strTime[$i] . "(s) ago ";
    }
}

function only($role){
    my_session_start();

    if(! has_session()){
        redirect(base_url() . "/views/auth/login.php");
    }
    
    if(strtolower($_SESSION['role']) != strtolower($role)){
        redirect(dashboard());
    }
}