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
    "line_message_types" => [
        "text",
        "sticker",
        "image",
        "video",
        "audio",
        "location",
        "imagemap",
        "template",
    ],

    "platform_types" => [
        [
            "id" => hash("sha256", "Play Station 4"),
            "value" => "Play Station 4",
        ],
        [
            "id" => hash("sha256", "Play Station 5"),
            "value" => "Play Station 5",
        ],
        [
            "id" => hash("sha256", "Nintendo Switch Lite"),
            "value" => "Nintendo Switch Lite",
        ],
        [
            "id" => hash("sha256", "Nintendo Switch"),
            "value" => "Nintendo Switch",
        ],
        [
            "id" => hash("sha256", "Xbox"),
            "value" => "Xbox",
        ],
        [
            "id" => hash("sha256", "Xbox 360"),
            "value" => "Xbox 360",
        ],
        [
            "id" => hash("sha256", "Xbox One"),
            "value" => "Xbox One",
        ],
        [
            "id" => hash("sha256", "Xbox Series X/S"),
            "value" => "Xbox Series X/S",
        ],
        [
            "id" => hash("sha256", "PC"),
            "value" => "PC",
        ],
        [
            "id" => hash("sha256", "Mobile Phone"),
            "value" => "Mobile Phone",
        ],
    ],

    // プレイ頻度
    "frequency_types" => [
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
