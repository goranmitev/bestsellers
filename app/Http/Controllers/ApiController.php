<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\GetBestsellersHistory;
use App\NYT\BestsellersHistory;

class ApiController extends Controller
{

    public function bestsellers(GetBestsellersHistory $request)
    {

        $api = new BestsellersHistory();

        $response = $api->getBestsellersHistory($request->validated());

        return $response;
        
    }
}
