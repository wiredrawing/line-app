<?php




return [

    // true or falseフラグ
    "binary_type" => [
        "on" => 1,
        "off" => 0,
    ],


    // --------------------------------------------------
    // LINE連携時のAPIエンドポイント
    // --------------------------------------------------
    "line_login" => [
        "authorize" => "https://access.line.me/oauth2/v2.1/authorize",
        "token" => "https://api.line.me/oauth2/v2.1/token",
        "verify" => "https://api.line.me/oauth2/v2.1/verify",
        "push" => "https://api.line.me/v2/bot/message/push",
        "multicast" => "https://api.line.me/v2/bot/message/multicast",
    ],
    "line_message_type_list" => [
        "text",
        "sticker",
        "image",
        "video",
        "audio",
        "location",
        "imagemap",
        "template",
    ],

    "platform_list" => [
        [
            "id" => 10,
            "value" => "Play Station 4",
        ],
        [
            "id" => 20,
            "value" => "Play Station 5",
        ],
        [
            "id" => 30,
            "value" => "Nintendo Switch Lite",
        ],
        [
            "id" => 40,
            "value" => "Nintendo Switch",
        ],
        [
            "id" => 50,
            "value" => "Xbox",
        ],
        [
            "id" => 60,
            "value" => "Xbox 360",
        ],
        [
            "id" => 70,
            "value" => "Xbox One",
        ],
        [
            "id" => 80,
            "value" => "Xbox Series X/S",
        ],
        [
            "id" => 90,
            "value" => "PC",
        ],
        [
            "id" => 100,
            "value" => "Mobile Phone",
        ],
    ],
    "platform_code" => [
        "PS4" => 10,
        "PS5" => 20,
        "S_LIST" => 30,
        "S" => 40,
        "XBOX" => 50,
        "XBOX360" => 60,
        "XBOX_ONE" => 70,
        "XBOX_XS" => 80,
        "PC" => 90,
        "MOBILE" => 100,
    ],

    "genre_list" => [
        [
            "id" => 10,
            "value" => "RPG",
        ],
        [
            "id" => 20,
            "value" => "STG",
        ],
        [
            "id" => 30,
            "value" => "ACTION",
        ],
        [
            "id" => 40,
            "value" => "FPS",
        ],
        [
            "id" => 50,
            "value" => "TPS",
        ],
        [
            "id" => 60,
            "value" => "SLG",
        ],
    ],
    "genre_code" => [
        "RPG" => 10,
        "STG" => 20,
        "ACT" => 30,
        "FPS" => 40,
        "TPS" => 50,
        "SLG" => 60,
    ],

    // プレイ頻度
    "frequency_list" => [
        [
            "id" => 1,
            "value" => "週1回",
        ],
        [
            "id" => 2,
            "value" => "週2回",
        ],
        [
            "id" => 3,
            "value" => "週3回",
        ],
        [
            "id" => 4,
            "value" => "週4回",
        ],
        [
            "id" => 5,
            "value" => "週5回",
        ],
        [
            "id" => 6,
            "value" => "週6回",
        ],
        [
            "id" => 7,
            "value" => "週7回",
        ],
    ],
];
