# POS System (نظام كاشير)

REST API لنظام نقاط بيع (كاشير) مبني بـ Laravel 13 و PHP 8.3.

## المميزات

- **تسجيل الدخول** - بالإيميل/الباسورد أو بكود PIN، باستخدام Laravel Sanctum.
- **الأصناف (Categories)** - CRUD كامل مع رفع صور.
- **المنتجات (Products)** - CRUD كامل مع رفع صور.
- **الطلبات والمدفوعات** - إدارة الطلبات وعناصر الطلب والمدفوعات.
- **الصلاحيات** - نظام أدوار وصلاحيات (Roles & Abilities).

## التقنيات

- **Framework:** Laravel 13
- **PHP:** 8.3+
- **Authentication:** Laravel Sanctum
- **Architecture:** Repository Pattern + Service Layer

## هيكل المشروع

```
app/
├── Http/
│   ├── Controllers/Api/    # Auth, Category, Product
│   ├── Requests/Api/       # Form Request Validation
│   └── Resources/Api/      # API Resources
├── Models/                 # User, Role, Ability, Category, Product, Order, OrderItem, Payment
├── Repositories/Api/
│   ├── Contracts/          # Repository Interfaces
│   └── Eloquent/           # Repository Implementations
├── Services/Api/           # Business Logic
└── Traits/                 # ApiResponse
```

## API Endpoints

### Auth
| Method | Endpoint         | Description              |
|--------|------------------|--------------------------|
| POST   | `/api/login`     | تسجيل دخول بالإيميل     |
| POST   | `/api/login/pin` | تسجيل دخول بكود PIN     |
| POST   | `/api/logout`    | تسجيل خروج              |
| GET    | `/api/me`        | بيانات المستخدم الحالي   |

### Categories
| Method | Endpoint                | Description   |
|--------|-------------------------|---------------|
| GET    | `/api/categories`       | كل الأصناف   |
| POST   | `/api/categories`       | إضافة صنف    |
| GET    | `/api/categories/{id}`  | عرض صنف      |
| PUT    | `/api/categories/{id}`  | تعديل صنف    |
| DELETE | `/api/categories/{id}`  | حذف صنف      |

### Products
| Method | Endpoint               | Description   |
|--------|------------------------|---------------|
| GET    | `/api/products`        | كل المنتجات  |
| POST   | `/api/products`        | إضافة منتج   |
| GET    | `/api/products/{id}`   | عرض منتج     |
| PUT    | `/api/products/{id}`   | تعديل منتج   |
| DELETE | `/api/products/{id}`   | حذف منتج     |

## التثبيت

```bash
git clone <repo-url>
cd pos-system
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```
