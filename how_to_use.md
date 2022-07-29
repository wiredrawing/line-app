
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

## 指定したLINEチャネルに任意のメッセージを複数送信する

**http://localhost:8000/admin/api/line/reserve/reserve/{line_reserve_id}**

```post.json
{
    "api_token": "Qeuj9cXlCObWlbR4LcGR4SUdzB96r2XazndXzKTMeC69zhuuPp6soxIPdOttJwMEhnV81qs5PAvJtuX_tgtyJEkkblsLMFH2"
}
```
