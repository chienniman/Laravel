<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ApiDemo;

class ApiDemoController extends Controller
{
    ApiDemo::dispatch();
}
