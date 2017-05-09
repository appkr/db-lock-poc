# 데이터베이스 잠금을 실험하기 위한 프로젝트

선점 잠금(Pessimistic Lock)과 비선점 잠금(Optimistic Lock)을 실험하기 위한 프로젝트입니다. 

선점 잠금이란 사용자 A가 레코드를 업데이트할 목적으로 조회하면서 업데이트가 끝날 때까지 레코드를 잠궈 두는 것을 말합니다. 동시에 다른 사용자 B가 같은 레코드에 대해 잠금을 구하고자 하면 A가 선점한 잠금이 풀릴 때까지 레코드를 조회하거나 업데이트할 수 없으며, A의 업데이트가 끝나면 그제서야 업데이트된 레코드를 사용자 B가 얻을 수 있습니다. 가령, 재고가 한 개 남은 제품을 사용자 A와 B가 동시에 구매하는 상황을 가정해 볼 수 있습니다. A가 구매하면서 시스템이 재고 수량을 0으로 바꾸면, 사용자 B는 도메인 규칙(재고가 있을 때만 구매할 수 있다)에 의해서 예외 응답을 받게 됩니다.

비 선점 잠금이란 레코드를 업데이트하는 순간에 업데이트 가능성을 판단하는 방법입니다. 사용자가 A와 B가 제품 구매를 위해 조회한 레코드 버전이 1인데, 사용자 B가 먼저 구매하면서 레코드의 버전이 2로 바뀌었다면, 사용자 A는 예외 응답을 받게 됩니다. 데이터베이스 레코드를 업데이트할 때 조회한 레코드의 버전을 조건절에 포함하여 구현합니다(`UPDATE products SET stock = 0, version = version + 1 WHERE version = 1 AND id = 1`).

이 실험 프로젝트에서는 라라벨 프레임워크와 Pseudo DDD(Domain Driven Design) 설계를 적용하여, 선점 잠금과 비선점 잠금을 구현하고 있습니다.

이 실험 프로젝트를 진행한 후, 알게된 엄청난 사실은... 다음 링크에서 확인해 주세요.
http://stackoverflow.com/questions/15872326/php-mysql-does-mysql-auto-lock-rows-when-updating

## 프로젝트 설치

프로젝트를 복제하고 컴포저 컴포넌트를 설치합니다.

```bash
~ $ git clone git@github.com:appkr/db-lock-poc.git && cd db-lock-poc
~/db-lock-poc $ composer install
~/db-lock-poc $ cp .env.example .env
~/db-lock-poc $ php artisan key:generate
```

`.env` 파일을 열어 MySQL 접속 정보를 설정합니다.

```bash
# .env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_lock_poc
DB_USERNAME=homestead
DB_PASSWORD=secret
# ...
```

데이터베이스 스키마를 초기화하고, 테스트 데이터를 생성합니다.

```bash
~/db-lock-poc $ php artisan migrate --seed
```

웹 서버를 구동하고 확인합니다.

```bash
~/db-lock-poc $ php artisan serve
# Laravel development server started: <http://127.0.0.1:8000>
```

## 테스트

포스트맨 A, B 두 개를 이용하여, `UpdateProductRequest` 요청을 순차적으로 실행합니다. 선점한 프로세스 A가 끝나고 DB 잠금이 풀리면, 다음 프로세스 B를 처리하는 것을 확인합니다.
 
- 포스트맨 콜렉션 : https://www.getpostman.com/collections/af29606934f604e83ec2
- 포스트맨 환경 : https://raw.githubusercontent.com/appkr/db-lock-poc/master/docs/DB-LOCK-POC.postman_environment.json

```php
<?php
// app/Http/Controllers/ProductController.php

class ProductController extends Controller
{
    // ...
    
    public function update(UpdateProductRequest $request, int $productId)
    {
        // [선점 잠금] 레코드를 조회하고 잠급니다.
        // $product = $this->productRepository->findByIdWithLock($productId);

        // [선점 잠금] PoC를 위해 강제로 잠금을 연장합니다.
        // 선점한 프로세스 A가 끝나고 DB 잠금이 풀리면, 다음 프로세스 B를 처리합니다.
        // sleep(10);

        // [비선점 잠금]
        // 조회시점 대비 DB의 버전이 같은지를 확인하여 변경 가능 여부를 판단합니다.
        $product = $this->productRepository->findById($productId);
        sleep(10);

        $product = $this->productService->modifyProduct(
            $product, $request->getProductDto()
        );

        return json()->withItem($product, new ProductTransformer());
    }
}
```

## Sponsor

[Jetbrains](https://www.jetbrains.com/) 사에서 IntelliJ IDE를 제공해주셨습니다.

![](intellij_logo.png)