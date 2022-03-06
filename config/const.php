<?php




return [

    // --------------------------------------------------
    // LINE連携時のAPIエンドポイント
    // --------------------------------------------------
    "line_login" => [
        "authorize" => "https://access.line.me/oauth2/v2.1/authorize",
        "token" => "https://api.line.me/oauth2/v2.1/token",
        "verify" => "https://api.line.me/oauth2/v2.1/verify",
        "push" => "https://api.line.me/v2/bot/message/push",
    ]
];
