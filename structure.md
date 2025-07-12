api_itravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── api/
│   │   │   │   ├── v1/
│   │   │   │   │   ├── AdminController.php
│   │   │   │   │   ├── AuthController.php
│   │   │   │   │   └── CategoryController.php
│   │   │   │   └── BaseApiController.php
│   │   │   └── Controller.php
│   │   ├── Middleware/
│   │   ├── Requests/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginRequest.php
│   │   │   │   └── RegisterRequest.php
│   │   │   ├── Category/
│   │   │       └── CategoryRequest.php
│   │   ├── Resources/
│   │       └── CategoryResource.php
│   ├── Macros/
│   │   └── ResponseMacro.php
│   ├── Models/
│   │   ├── Auth/
│   │   │   ├── Admin.php
│   │   │   └── LoginAttempt.php
│   │   ├── Category/
│   │   │   └── Category.php
│   │   └── Admin.php
│   ├── Providers/
│       ├── AppServiceProvider.php
│       └── MacroServiceProvider.php
├── bootstrap/
│   ├── cache/
│   │   ├── packages.php
│   │   └── services.php
│   ├── app.php
│   └── providers.php
├── config/
│   ├── api.php
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── cors.php
│   ├── database.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── permission.php
│   ├── queue.php
│   ├── sanctum.php
│   ├── services.php
│   └── session.php
├── database/
│   ├── factories/
│   │   └── UserFactory.php
│   ├── migrations/
│   │   ├── 2025_06_28_052333_create_admins_table.php
│   │   ├── 2025_06_28_054506_create_personal_access_tokens_table.php
│   │   ├── 2025_06_28_054618_create_cache_table.php
│   │   ├── 2025_06_28_060003_create_jobs_table.php
│   │   ├── 2025_06_28_060119_create_sessions_table.php
│   │   ├── 2025_06_28_071901_create_login_attempts_table.php
│   │   ├── 2025_06_28_090708_create_categories_table.php
│   │   └── 2025_06_28_153722_create_personal_access_tokens_table.php
│   ├── seeders/
│   └── database.sqlite
├── public/
│   ├── favicon.ico
│   ├── index.php
│   └── robots.txt
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   ├── views/
│       └── welcome.blade.php
├── routes/
│   ├── api.php
│   ├── console.php
│   └── web.php
├── storage/
│   ├── app/
│   │   ├── private/
│   │   ├── public/
│   ├── framework/
│   │   ├── cache/
│   │   │   ├── data/
│   │   ├── sessions/
│   │   ├── testing/
│   │   ├── views/
│   │       ├── 0fc5987ca9503f6c5666fda875909e6e.php
│   │       ├── 1742bbee930b64c0d9d128971a513265.php
│   │       ├── 1e02d4b2da3b55c37a38dbc69ef76cf5.php
│   │       ├── 368dc8c151f22d5a5da17ebde27ba07c.php
│   │       ├── 5b7c40297673231ba1ce16844bfc152e.php
│   │       ├── 7221014cdd748a01f4c677fa72132084.php
│   │       ├── 7bb9fc94bfbc56be7128b0a0ca9d0a6c.php
│   │       ├── bbd867e719cd9031dcb7c4028a15cad9.php
│   │       ├── c5a589150fb0895d7fd91592255e1e0e.php
│   │       ├── e4d31ce6b27018a3e10fdcf3a8095a0c.php
│   │       ├── e95fe20c9ff413aa66ddbae6b9272fb6.php
│   │       ├── e9febfb39c6df29c3fbbc087e5b22d96.php
│   │       ├── eafa81add7638c75079ef6b889fac171.php
│   │       ├── f5f1ff88477f461746c9594f26369d4e.php
│   │       ├── f7413fd00a520d3e5a1704da1f3c8667.php
│   │       ├── f93dd9ebe74f18dab2a3c3353428f51a.php
│   │       └── ffd0131f5a3182e7f72f5fbd24f60f6e.php
│   ├── logs/
├── tests/
│   ├── Feature/
│   │   └── ExampleTest.php
│   ├── Unit/
│   │   └── ExampleTest.php
│   └── TestCase.php
├── API_STRUCTURE_README.md
├── README.md
├── artisan
├── composer.json
├── composer.lock
├── db_itravel
├── package.json
├── phpunit.xml
├── structure.md
└── vite.config.js
