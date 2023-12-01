<?php

/** 
 * routes.php
 *  Examines the requested URL and dynamically assigns a corresponding view based on the 
 *  specified destination.
*/

$request = $_SERVER['REQUEST_URI'];

// Begin session
if(session_status() == 1)
{
    session_start();
}

// Exploding '?' to just get the request
if(str_contains($request, '?') == true)
{
    $url = current(explode('?', $request));
} else {
    $url = $request;
}

// Switch
switch ($url) {
    case '/':
        require __DIR__ . '/Resources/Views/Pages/landing.php';
        break;
    default: // Any: 404
        http_response_code(404);
        require __DIR__ . '/Resources/Views/Pages/other/404.php';
        break;
}