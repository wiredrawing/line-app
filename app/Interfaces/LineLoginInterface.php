<?php

namespace App\Interfaces;



interface  LineLoginInterface {


    // Authorization using callback url.
    public function authenticate();
    public function fetchAccessToken();

}
