[docs/](docs/) 폴더에서 포스트맨 스크린샷으로도 확인할 수 있습니다.  

#### 3.2.1. TC#1

```json
// A: after 10 sec sleep
{
    "id": 12,
    "title": "TEST CASE 1 LEADING REQUEST",
    "stock": 10,
    "price": 1000,
    "description": "TEST DESCRIPTION",
    "version": 2,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:25:01+0000"
}
```

```json
// B: immediately after the Leading Request fihishes
{
    "id": 12,
    "title": "TEST CASE 1 LEADING REQUEST",
    "stock": 10,
    "price": 2000,
    "description": "TEST CASE 1 FOLLOWING REQUEST",
    "version": 2,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:25:02+0000"
}
```

#### 3.2.2. TC#2

```json
// A: after 10 sec sleep
{
    "id": 12,
    "title": "TEST CASE 2 LEADING REQUEST",
    "stock": 10,
    "price": 2000,
    "description": "TEST CASE 1 FOLLOWING REQUEST",
    "version": 3,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:28:32+0000"
}
```

```json
// B: immediately after the Leading Request fihishes
{
    "id": 12,
    "title": "TEST CASE 2 LEADING REQUEST",
    "stock": 10,
    "price": 3000,
    "description": "TEST CASE 2 FOLLOWING REQUEST",
    "version": 3,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:28:33+0000"
}
```

#### 3.2.3. TC#3

```json
// A: after 10 sec sleep
{
    "id": 12,
    "title": "TEST CASE 3 LEADING REQUEST",
    "stock": 10,
    "price": 3000,
    "description": "TEST CASE 2 FOLLOWING REQUEST",
    "version": 4,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:30:56+0000"
}
```

```json
// B: immediately after the Leading Request fihishes
{
    "id": 12,
    "title": "TEST CASE 3 LEADING REQUEST",
    "stock": 10,
    "price": 4000,
    "description": "TEST CASE 3 FOLLOWING REQUEST",
    "version": 4,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:30:57+0000"
}
```

#### 3.2.4. TC#4

```json
// A: after 10 sec sleep
{
    "id": 12,
    "title": "TEST CASE 4 LEADING REQUEST",
    "stock": 10,
    "price": 4000,
    "description": "TEST CASE 3 FOLLOWING REQUEST",
    "version": 5,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:33:44+0000"
}
```

```json
// B: immediately after the Leading Request finishes
{
    "message": "데이터를 조회한 후에 다른 사용자에 의해 데이터가 변경되었습니다."
}
```

#### 3.2.5. TC#5

```json
// A: after 10 sec sleep
{
    "id": 12,
    "title": "TEST CASE 5 LEADING REQUEST",
    "stock": 10,
    "price": 4000,
    "description": "TEST CASE 3 FOLLOWING REQUEST",
    "version": 6,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:37:50+0000"
}
```

```json
// B: immediately after the Leading Request finishes
{
    "id": 12,
    "title": "TEST CASE 5 LEADING REQUEST",
    "stock": 10,
    "price": 6000,
    "description": "TEST CASE 5 FOLLOWING REQUEST",
    "version": 6,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:37:52+0000"
}
```

#### 3.2.6. TC#6

```json
// A: after 10 sec sleep
{
    "id": 12,
    "title": "TEST CASE 6 LEADING REQUEST",
    "stock": 10,
    "price": 6000,
    "description": "TEST CASE 5 FOLLOWING REQUEST",
    "version": 7,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:39:53+0000"
}
```

```json
// B: after 10 sec sleep
{
    "id": 12,
    "title": "TEST CASE 6 LEADING REQUEST",
    "stock": 10,
    "price": 7000,
    "description": "TEST CASE 6 FOLLOWING REQUEST",
    "version": 8,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:40:03+0000"
}
```

#### 3.2.7. TC#7

```json
// A: after 10 sec sleep
{
    "id": 12,
    "title": "TEST CASE 7 LEADING REQUEST",
    "stock": 10,
    "price": 7000,
    "description": "TEST CASE 6 FOLLOWING REQUEST",
    "version": 9,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:42:19+0000"
}
```

```json
// B: immediately after the Leading Request finishes
{
    "message": "SQLSTATE[40001]: Serialization failure: 1213 Deadlock found when trying to get lock; try restarting transaction (SQL: update `products` set `description` = TEST CASE 7 FOLLOWING REQUEST, `version` = 9, `updated_at` = 2017-09-10 02:42:20 where `id` = 12)"
}
```

#### 3.2.8. TC#8

```json
// A: after 10 sec sleep
{
    "id": 12,
    "title": "TEST CASE 8 LEADING REQUEST",
    "stock": 10,
    "price": 7000,
    "description": "TEST CASE 6 FOLLOWING REQUEST",
    "version": 10,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:46:25+0000"
}	
```

```json
// B: immediately after the Leading Request finishes
{
    "id": 12,
    "title": "TEST CASE 8 LEADING REQUEST",
    "stock": 10,
    "price": 9000,
    "description": "TEST CASE 8 FOLLOWING REQUEST",
    "version": 10,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:46:26+0000"
}
```

#### 3.2.9. TC#9

```json
// A: after 10 sec sleep
{
    "id": 12,
    "title": "TEST CASE 9 LEADING REQUEST",
    "stock": 10,
    "price": 12000,
    "description": "TEST CASE 11 FOLLOWING REQUEST",
    "version": 11,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:58:52+0000"
}
```

```json
// B: immediately after Leading Request finishes
{
    "message": "데이터를 조회한 후에 다른 사용자에 의해 데이터가 변경되었습니다."
}
```

#### 3.2.10. TC#10

```json
// A: after 10 sec sleep
{
    "id": 12,
    "title": "TEST CASE 10 LEADING REQUEST",
    "stock": 10,
    "price": 12000,
    "description": "TEST CASE 11 FOLLOWING REQUEST",
    "version": 12,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T03:00:40+0000"
}
```

```json
// B: immediately after Leading Request finishes
{
    "message": "데이터를 조회한 후에 다른 사용자에 의해 데이터가 변경되었습니다."
}
```

#### 3.2.11. TC#11

```json
// A: after 10 sec sleep
{
    "id": 12,
    "title": "TEST CASE 11 LEADING REQUEST",
    "stock": 10,
    "price": 10000,
    "description": "TEST CASE 9 FOLLOWING REQUEST",
    "version": 14,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:52:18+0000"
}
```

```json
// B: after 10 sec sleep
{
    "id": 12,
    "title": "TEST CASE 11 LEADING REQUEST",
    "stock": 10,
    "price": 12000,
    "description": "TEST CASE 11 FOLLOWING REQUEST",
    "version": 15,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:52:28+0000"
}
```

#### 3.2.12. TC#12

```json
// A: after 10 sec sleep
{
    "id": 12,
    "title": "TEST CASE 12 LEADING REQUEST",
    "stock": 10,
    "price": 12000,
    "description": "TEST CASE 11 FOLLOWING REQUEST",
    "version": 16,
    "created_at": "2017-09-10T02:20:03+0000",
    "updated_at": "2017-09-10T02:54:49+0000"
}	
```

```json
// B: immediately after Leading Request finishes
{
    "message": "SQLSTATE[40001]: Serialization failure: 1213 Deadlock found when trying to get lock; try restarting transaction (SQL: update `products` set `price` = 13000, `description` = TEST CASE 12 FOLLOWING REQUEST, `version` = 16, `updated_at` = 2017-09-10 02:54:51 where `id` = 12)"
}
```