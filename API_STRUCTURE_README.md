# API Structure - Frontend vs Backend Separation

## Tổng quan

Hệ thống API đã được tách riêng thành 2 phần chính:

-   **Frontend API**: Dành cho website công khai, chỉ đọc dữ liệu
-   **Backend/Admin API**: Dành cho quản trị viên, có đầy đủ CRUD

## Cấu trúc Routes

### 1. Frontend API (Public - Không cần auth)

```
/api/v1/frontend/categories/
├── GET /main                    # Lấy danh mục chính (parent_id = null)
├── GET /tree                    # Lấy cây danh mục hoàn chỉnh
├── GET /search?q=keyword        # Tìm kiếm danh mục
├── GET /{slug}                  # Lấy danh mục theo slug
├── GET /{parentSlug}/subcategories  # Lấy danh mục con
└── GET /{slug}/breadcrumb       # Lấy breadcrumb
```

### 2. Admin API (Cần xác thực)

```
/api/v1/admin/categories/
├── GET /                        # Danh sách với filter, search, pagination
├── POST /                       # Tạo danh mục mới
├── GET /{id}                    # Chi tiết danh mục
├── PUT /{id}                    # Cập nhật danh mục
├── DELETE /{id}                 # Xóa danh mục
├── GET /tree                    # Cây danh mục cho admin
└── POST /bulk-action            # Thao tác hàng loạt
```

## Lợi ích của việc tách riêng

### 🎯 **Frontend API**

-   **Đơn giản**: Chỉ có các endpoint cần thiết cho frontend
-   **Hiệu suất**: Chỉ lấy dữ liệu active, không cần pagination phức tạp
-   **Bảo mật**: Không có quyền thay đổi dữ liệu
-   **SEO friendly**: Có slug, breadcrumb cho SEO

### 🔧 **Admin API**

-   **Đầy đủ**: CRUD hoàn chỉnh với validation
-   **Linh hoạt**: Filter, search, pagination, sorting
-   **Quản lý**: Bulk actions, tree view cho admin
-   **Bảo mật**: Cần authentication và authorization

## Ví dụ sử dụng

### Frontend - Lấy menu chính

```javascript
// Lấy danh mục chính cho navigation
fetch("/api/v1/frontend/categories/main")
    .then((response) => response.json())
    .then((data) => {
        // Hiển thị menu chính
        console.log(data.data);
    });
```

### Frontend - Lấy cây danh mục

```javascript
// Lấy toàn bộ cây danh mục cho sidebar
fetch("/api/v1/frontend/categories/tree")
    .then((response) => response.json())
    .then((data) => {
        // Hiển thị sidebar menu
        console.log(data.data);
    });
```

### Frontend - Lấy breadcrumb

```javascript
// Lấy breadcrumb cho trang chi tiết
fetch("/api/v1/frontend/categories/du-lich/breadcrumb")
    .then((response) => response.json())
    .then((data) => {
        // Hiển thị breadcrumb: Trang chủ > Du lịch
        console.log(data.data);
    });
```

### Admin - Quản lý danh mục

```javascript
// Lấy danh sách với filter
fetch("/api/v1/admin/categories?status=active&parent_id=null&per_page=20")
    .then((response) => response.json())
    .then((data) => {
        // Hiển thị bảng quản lý
        console.log(data.data);
    });
```

### Admin - Bulk actions

```javascript
// Xóa nhiều danh mục cùng lúc
fetch("/api/v1/admin/categories/bulk-action", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
    },
    body: JSON.stringify({
        action: "delete",
        ids: [1, 2, 3],
    }),
});
```

## Response Format

### Frontend Response (Đơn giản)

```json
{
  "success": true,
  "message": "Lấy danh mục chính thành công",
  "data": [
    {
      "id": 1,
      "name": "Du lịch",
      "slug": "du-lich",
      "children": [...]
    }
  ]
}
```

### Admin Response (Chi tiết)

```json
{
  "success": true,
  "message": "Lấy danh sách danh mục thành công",
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 20,
    "total": 100,
    "last_page": 5
  }
}
```

## Cấu hình và Tùy chỉnh

### Thêm endpoint mới cho Frontend

```php
// Trong FrontendCategoryController
public function popularCategories()
{
    $categories = Category::where('status', 'active')
        ->orderBy('sort_order', 'asc')
        ->limit(5)
        ->get();

    return response()->json([
        'success' => true,
        'data' => CategoryResource::collection($categories)
    ]);
}

// Thêm route
Route::get('/categories/popular', [FrontendCategoryController::class, 'popularCategories']);
```

### Thêm endpoint mới cho Admin

```php
// Trong AdminCategoryController
public function statistics()
{
    $stats = [
        'total' => Category::count(),
        'active' => Category::where('status', 'active')->count(),
        'inactive' => Category::where('status', 'inactive')->count(),
        'with_children' => Category::has('children')->count(),
    ];

    return response()->json([
        'success' => true,
        'data' => $stats
    ]);
}

// Thêm route
Route::get('/categories/statistics', [AdminCategoryController::class, 'statistics']);
```

## Middleware và Bảo mật

### Frontend API

-   Không cần authentication
-   Có thể thêm rate limiting để tránh spam
-   Cache để tăng hiệu suất

### Admin API

-   Bắt buộc authentication (sanctum)
-   Có thể thêm role-based authorization
-   Logging cho audit trail

## Kết luận

Việc tách riêng Frontend và Backend API mang lại:

1. **Tính rõ ràng**: Mỗi API có mục đích cụ thể
2. **Bảo mật tốt hơn**: Frontend không thể thay đổi dữ liệu
3. **Hiệu suất cao**: Frontend chỉ lấy dữ liệu cần thiết
4. **Dễ bảo trì**: Code được tổ chức tốt hơn
5. **Mở rộng dễ dàng**: Có thể thêm tính năng mới cho từng phần
