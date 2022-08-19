
## LINEチャネルの登録

**http://localhost:8000/admin/api/line/account/create**

```post.json
{
    "channel_name": "LINEログインテスト",
    "channel_id": "1234567890",
    "channel_secret": "123456789abcdefghijklmnopqrstuxwz",
    "user_id": "U1234567891234567891234567891234",
    "messaging_channel_id": "1234567890",
    "messaging_channel_secret": "123456789abcdefghijklmnopqrstuxwz",
    "messaging_user_id": "123456789abcdefghijklmnopqrstuxwz",
    "messaging_channel_access_token": "....."
}
```

## 指定したLINEチャネルに現在登録中の未送信メッセージ一覧を取得する

**http://localhost:8000/admin/api/line/reserve/unsent/{line_account_id}**  

```ini
{line_account_id} => line_accountsテーブルのプライマリキー
```



## 指定したLINEチャンネルにメッセージの予約設定をする

**http://localhost:8000/admin/api/line/reserve/reserve/{line_account_id}**

```post.json
{
    "api_token": "Qeuj9cXlCObWlbR4LcGR4SUdzB96r2XazndXzKTMeC69zhuuPp6soxIPdOttJwMEhnV81qs5PAvJtuX_tgtyJEkkblsLMFH2",
    "messages": [
        {
            "type": "text",
            "text": "テストメッセージ"
        },
        {
            "type": "text",
            "text": "複数回に分けて送信する"
        }
    ],
    "delivery_datetime": "2022-07-29 10:30"
}
```

## 指定したLINEチャネルに任意のメッセージを複数送信する

**http://localhost:8000/admin/api/line/reserve/reserve/{line_reserve_id}**

```post.json
{
    "api_token": "Qeuj9cXlCObWlbR4LcGR4SUdzB96r2XazndXzKTMeC69zhuuPp6soxIPdOttJwMEhnV81qs5PAvJtuX_tgtyJEkkblsLMFH2"
}
```


## LINEログインしたline memberのアクセストークンをリフレッシュする
**http://localhost:8000/admin/api/line/refresh/index**

```post.json
{
    "api_token": "IKjigMwGY_-wINxr4sslhZohI49fBzxJQYQqVe1f_YzOKL6KZ161qAGDwzGsrW_6"
}
```

```response.json
{
    "status": true,
    "response": {
        "id": 1,
        "access_token": "...GhK0ECXfcWnhi3sJvdMSOgJ_5ycwnKDN8U4adx2aNhhDe__BG8Je04eqGaPB6nu6O...",
        "expires_in": 2592000,
        "id_token": "...aW5lLm1lIiwic3ViiM2MiwiaWF0IjoxNjYwODkzNzYyLCJub25jZSI6IjcwMDg5YTQyODRkNWNiNzhhZWIxZGY5ZWIxOTViMmM2YzExYjI0YmJhYmIwNDY4ZDJkM2I1MmE5YjhhNjNmNjciLCJhbXIiOlsibGluZXNzbyJdLCJuYW1lIjoiU2VuYmlraSBBa2lmdW1pIiwicGljdHVyZSI6Imh0dHBzOi8vcHJvZmlsZS5saW5lLXNjZG4ubmV0LzBoeGx6M0xibEtKME5IU2pFV0tUWllGSHNQS1M0d1pDRUxQeVZ0SkRGTEtYdGpjMmtTZW5nNGNHSlBLWEZpZTJZZGVTVTdkallkZTNwdSIsImVtYWlsIjoiYWtpZnVtaS5zZW5iaWtpLjEyMDlAZ...",
        "refresh_token": "...",
        "token_type": "Bearer",
        "email": "...",
        "picture": "https://profile.line-scdn.net/0hxlz3LblKJ0NHSjEWKTZYFHsPKS4wZCELPyVtJDFLKXtjc2kSeng4cGJPKXFie2YdeSU7djYde3pu",
        "name": "...",
        "is_displayed": 1,
        "sub": "...",
        "aud": "...",
        "line_account_id": 1,
        "api_token": "本アプリケーション独自のトークン",
        "created_at": "2022-08-19T07:22:40.000000Z",
        "updated_at": "2022-08-19T08:19:15.000000Z",
        "line_account": {
            "id": 1,
            "channel_name": "ゲームマッチングアプリ",
            "channel_id": "123456789",
            "channel_secret": "...",
            "user_id": "...",
            "messaging_channel_id": "...",
            "messaging_channel_secret": "...",
            "messaging_user_id": "...",
            "messaging_channel_access_token": "...",
            "webhook_url": "",
            "api_token": "",
            "application_key": "game_matching",
            "is_enabled": 1,
            "is_hidden": 0,
            "created_at": "2022-08-19T00:55:45.000000Z",
            "updated_at": "2022-08-19T00:55:48.000000Z"
        },
        "player": {
            "id": 1,
            "line_member_id": 1,
            "family_name": null,
            "middle_name": null,
            "given_name": null,
            "nickname": "LINEアカウントと連携する",
            "email": "LINEアカウントと連携する",
            "gender_id": null,
            "is_displayed": 1,
            "is_deleted": 0,
            "is_published": 0,
            "player_token": "本アプリケーション独自",
            "created_at": "2022-08-19T07:22:40.000000Z",
            "updated_at": "2022-08-19T07:22:40.000000Z"
        }
    }
}
```



<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
