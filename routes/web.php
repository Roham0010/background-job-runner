<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    runBackgroundJob('Email', 'sendEmail', ['email' => "email@gmail.com", 'subject' => 'sbj', 'message' => 'msg']);
});
