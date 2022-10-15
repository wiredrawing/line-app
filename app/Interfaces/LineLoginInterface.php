<?php

namespace App\Interfaces;



interface  LineLoginInterface {


    // Authorization using callback url.
    public function authenticate();
    public function fetchAccessToken();
    public function makeJsonWebToken(int $player_id, string $api_token):?string;

    public function addUserTable();
}
