# proptier-pre-assignment
프롭티어 사전 과제


## Description
이 프로젝트는 Laravel 12 이상과 PHP 8.1+ 환경에서 동작하는 회원기능 및  게시판 API를 구현하는 사전 과제입니다.
CRUD 기본기, 데이터베이스 모델링, API 문서 작성 능력을 확인하는 것이 목적입니다.


구현 범위:
- 사용자 회원가입 / 로그인 / 로그아웃 / 탈퇴 
- 게시글(Board) CRUD
- 댓글(Comment) CRUD (Optional)
- 게시글 좋아요 등록/해제
- 페이지네이션
- 요청 데이터 유효성 검사
- 공통 JSON 응답 포맷 적용


# Steps
```bash

# 1. 의존성 설치
composer install

# 2. 환경 설정
cp .env.example .env
php artisan key:generate

# 3. DB 마이그레이션
php artisan migrate
※ 전체 초기화후 재실행(선택)
php artisan migrate:fresh

# 4. 서버 실행
php artisan serve[collection](postman/collection)
```

---

### Usage (Postman Collection)

* endPoint : [http://localhost:8000](http://localhost:8000)
* loginToken : 로그인 성공 토큰
* env 설정파일 : `postman/proptier_env.postman_environment`
* `postman/collection.json` 에 API 테스트를 위한 Postman Collection 이 포함됩니다.

---

### 설계 및 개발 환경

* **Language** : PHP 8.2
* **Framework** : Laravel 12.x
* **Database (RDBMS)** : MySQL 8.0
* **Authentication** : Laravel Sanctum (Token 기반 인증)
* **Password Hashing** : bcrypt
* **ORM** : Eloquent ORM
* **패키지 관리** : Composer
* **기타** : Postman을 이용한 API 테스트, 마이그레이션 & 시더로 초기 데이터 구성

---

