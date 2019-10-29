<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $host = request()->getHost();
    $myFrontend = "http://" . $host . ":8081";
    $content = "<html lang=\"en\">
<head>
<title>Unauthorized</title>
<style>body{background-color:#333;color:white;}</style>
</head>
<body>
<h2>
You are not allowed to access the Hangman API without authorization and without using the frontend application.
</h2>
<br/>
<h3>
Please login <a style=\"color:blue\" target=\"_blank\" rel=\"noreferrer noopener\" href=\" $myFrontend\">here</a>
</h3>
</body>
</html>";
    return response($content, 403);
});
