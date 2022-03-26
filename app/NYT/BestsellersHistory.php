<?php

namespace App\NYT;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class BestsellersHistory
{

    public $endpoint = '/lists/best-sellers/history.json';

    public function getBestsellersHistory($params = []) {

        $url = config('app.nyt_api_url') . $this->endpoint;

        $params['api-key'] = config('app.nyt_api_key');

        return Http::get($url, $params);
    }
}
