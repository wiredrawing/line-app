
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

## 指定したLINEチャネルに登録済みの未配信メッセージ一覧を返却する

**http://localhost:8000/admin/api/line/reserve/unsent/{line_account_id}**

```response.json
{
    "status": true,
    "response": [
        {
            "id": 5,
            "line_account_id": 8,
            "is_displayed": 1,
            "delivery_datetime": "2022-08-01T10:00:00.000000Z",
            "is_sent": 0,
            "created_at": "2022-08-01T07:55:44.000000Z",
            "updated_at": "2022-08-01T07:55:44.000000Z",
            "line_messages": [
                {
                    "id": 8,
                    "line_reserve_id": 5,
                    "is_displayed": 1,
                    "type": "text",
                    "text": "1",
                    "created_at": "2022-08-01T07:55:44.000000Z",
                    "updated_at": "2022-08-01T07:55:44.000000Z"
                },
                {
                    "id": 9,
                    "line_reserve_id": 5,
                    "is_displayed": 1,
                    "type": "text",
                    "text": "2",
                    "created_at": "2022-08-01T07:55:44.000000Z",
                    "updated_at": "2022-08-01T07:55:44.000000Z"
                },
                {
                    "id": 10,
                    "line_reserve_id": 5,
                    "is_displayed": 1,
                    "type": "text",
                    "text": "3",
                    "created_at": "2022-08-01T07:55:44.000000Z",
                    "updated_at": "2022-08-01T07:55:44.000000Z"
                }
            ]
        }
    ]
}
```

## 指定したLINEチャネルに任意のメッセージを複数送信する

**http://localhost:8000/admin/api/line/reserve/reserve/{line_reserve_id}**

```post.json
{
    "api_token": "Qeuj9cXlCObWlbR4LcGR4SUdzB96r2XazndXzKTMeC69zhuuPp6soxIPdOttJwMEhnV81qs5PAvJtuX_tgtyJEkkblsLMFH2"
}
```


## 登録済みのLINEチャネル一覧を返却

**http://localhost:8000/admin/api/line/account/list**

```response.json
{
    "status": true,
    "response": [
        {
            "id": 7,
            "channel_name": "LINEログインテスト",
            "channel_id": "something",
            "channel_secret": "something",
            "user_id": "something",
            "messaging_channel_id": "something",
            "messaging_channel_secret": "something",
            "messaging_user_id": "something",
            "messaging_channel_access_token": "something+something/something/something+something+something/something/1O/something=",
            "webhook_url": null,
            "api_token": "something",
            "application_key": "something",
            "is_enabled": 1,
            "is_hidden": 0,
            "created_at": "2022-07-28T23:45:42.000000Z",
            "updated_at": "2022-07-28T23:45:42.000000Z"
        }
    ],
    "errors": null
}
```

## 指定したLINEチャネルの詳細情報を取得

**http://localhost:8000/admin/api/line/account/detail/{line_account_id}/{api_token}**

※前述のAPIと基本レイアウトは同じ
```response.json
{
    "status": true,
    "response": [
        {
            "id": 7,
            "channel_name": "LINEログインテスト",
            "channel_id": "something",
            "channel_secret": "something",
            "user_id": "something",
            "messaging_channel_id": "something",
            "messaging_channel_secret": "something",
            "messaging_user_id": "something",
            "messaging_channel_access_token": "something+something/something/something+something+something/something/1O/something=",
            "webhook_url": null,
            "api_token": "something",
            "application_key": "something",
            "is_enabled": 1,
            "is_hidden": 0,
            "created_at": "2022-07-28T23:45:42.000000Z",
            "updated_at": "2022-07-28T23:45:42.000000Z"
        }
    ],
    "errors": null
}
```
