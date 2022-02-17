<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\Traits\RequestHelper;

class ApiController extends Controller
{
    use ApiResponser, RequestHelper;
}
