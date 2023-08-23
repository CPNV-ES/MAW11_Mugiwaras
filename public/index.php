<?php
//=============================================================================
// Entry script for ooless framework web apps.
// Author:  Pascal Hurni
// Date:    03-05-2014
//=============================================================================
// Do not modify this file except to adapt the URL fetching for URL rewriting.
// Fill up these files:
//    src/dispatcher.php
//    src/handler.php
//    src/renderer.php
//=============================================================================

session_start();

define('BASE_DIR', dirname( __FILE__ ).'/..');
define('SOURCE_DIR', BASE_DIR.'/src');

//=============================================================================
// Create the BAG which will contain the request/response meta data

$bag = [];

//=============================================================================
// Extract the route from a friendly URL

$route = $_SERVER["REQUEST_URI"];
if (!empty($_SERVER["QUERY_STRING"])) {
    $route = substr($route, 0, strlen($_SERVER["REQUEST_URI"])-strlen($_SERVER["QUERY_STRING"])-1);
}

$bag['route'] = $route;
$bag['method'] = $_SERVER['REQUEST_METHOD'];

error_log("ooless:index: ".$bag['method']." ".$bag['route']);

//=============================================================================
// Dispatch the request

require SOURCE_DIR.'/dispatcher.php';
$bag = dispatch($bag);

//=============================================================================
// Call the handler

require SOURCE_DIR.'/handler.php';
$bag = handle($bag);

//=============================================================================
// Render the response

require SOURCE_DIR.'/renderer.php';
render($bag);
