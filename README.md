markdown_content = """# BookStore API Service

這是一個基於 **CodeIgniter 4** 框架開發的 RESTful API 專案，書籍管理系統。專案中導入了 **JWT (JSON Web Token)** 身份驗證機制，並利用 **Redis** 優化頻繁讀取的數據查詢。

## 技術棧
* **Backend:** PHP 8.x / CodeIgniter 4
* **Database:** MySQL 8.x
* **Caching:** Redis
* **Authentication:** JWT (Firebase/php-jwt)
* **Tools:** Composer, Docker

## 核心功能
* **RESTful 資源管理：** 實現完整的書籍、分類與使用者 CRUD 操作。
* **JWT 安全驗證：** 透過 Service Layer 封裝驗證邏輯，確保 API 調用安全性。
* **Redis 快取機制：** 針對熱點數據（如書籍清單）實作快取，大幅降低數據庫負載並提升回應速度。
* **系統架構優化：**
    * 採用 **MVC** 架構與 **Service Layer** 分層，確保程式碼具備高擴展性與可測試性 。
    * 使用 **Database Indexing** 與語法調校，優化複雜查詢的執行效率。

## 快速啟動

### 1. 環境配置
複製並重新命名環境變數檔案：
```bash
cp env .env
```

### 2. 安裝套件
使用 Composer 安裝所有相依依賴項目 ：
```bash
composer install
```

### 3. 資料庫遷移與填充
執行 Migration 建立資料表結構 ：
```bash
php spark migrate
php spark db:seed BookSeeder
```
## API 文件

### 快速概覽
本專案提供以下核心 API 節點，支援 REST 風格的資源操作。所有 API 調用需要使用 **Content-Type: application/json** 標頭。

### 認證 API

#### 1. 使用者登入
**端點：** `POST /api/login`

登入並取得 JWT Token，用於後續 API 調用的身份驗證。

**請求體：**
```json
{
  "email": "user@example.com",
  "password": "your_password"
}
```

**成功響應 (200)：**
```json
{
  "status": 200,
  "message": "Login successful",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "user": {
    "id": 1,
    "email": "user@example.com",
    "name": "John Doe",
    "role": "user"
  }
}
```

**錯誤響應 (401)：**
```json
{
  "status": 401,
  "message": "Invalid credentials"
}
```

---

### 書籍管理 API

#### 2. 取得所有書籍
**端點：** `GET /api/books`

取得所有書籍資訊。此端點支援 Redis 快取機制，大幅提升性能。

**Query 參數：**
| 參數 | 類型 | 必填 | 說明 |
|------|------|------|------|
| page | int | 否 | 頁碼（預設：1） |
| limit | int | 否 | 每頁筆數（預設：20） |
| category_id | int | 否 | 分類篩選 |
| sort | string | 否 | 排序方式（price_asc, price_desc） |

**成功響應 (200)：**
```json
{
  "status": 200,
  "message": "Books retrieved successfully",
  "data": [
    {
      "id": 1,
      "title": "PHP 設計模式",
      "author": "Gang of Four",
      "price": 599.99,
      "category_id": 1,
      "stock": 50,
      "created_at": "2024-01-15T10:30:00Z"
    }
  ],
  "pagination": {
    "page": 1,
    "limit": 20,
    "total": 100
  }
}
```

**備註：** 此響應會被 Redis 快取 5 分鐘，後續相同請求將直接返回快取數據。

---

#### 3. 新增書籍
**端點：** `POST /api/books`

新增一本新書籍到系統。需要 **管理員權限** 且需在請求標頭中提供有效的 JWT Token。

**請求標頭：**
```
Authorization: Bearer <JWT_TOKEN>
Content-Type: application/json
```

**請求體：**
```json
{
  "title": "高級 PHP 開發",
  "author": "Expert Developer",
  "description": "深入了解 PHP 高級特性",
  "price": 899.99,
  "category_id": 2,
  "stock": 100,
  "isbn": "978-3-16-148410-0"
}
```

**成功響應 (201)：**
```json
{
  "status": 201,
  "message": "Book created successfully",
  "data": {
    "id": 101,
    "title": "高級 PHP 開發",
    "author": "Expert Developer",
    "price": 899.99,
    "created_at": "2024-06-02T10:35:00Z"
  }
}
```

**錯誤響應 (403)：**
```json
{
  "status": 403,
  "message": "Permission denied. Admin access required."
}
```

---

#### 4. 編輯書籍
**端點：** `PUT /api/books/{id}`

編輯現有書籍資訊。需要 **管理員權限** 且需在請求標頭中提供有效的 JWT Token。

**URL 參數：**
| 參數 | 類型 | 說明 |
|------|------|------|
| id | int | 書籍 ID |

**請求標頭：**
```
Authorization: Bearer <JWT_TOKEN>
Content-Type: application/json
```

**請求體（可選擇性更新）：**
```json
{
  "title": "高級 PHP 開發 第二版",
  "price": 999.99,
  "stock": 80
}
```

**成功響應 (200)：**
```json
{
  "status": 200,
  "message": "Book updated successfully",
  "data": {
    "id": 101,
    "title": "高級 PHP 開發 第二版",
    "price": 999.99,
    "updated_at": "2024-06-02T11:20:00Z"
  }
}
```

**錯誤響應 (404)：**
```json
{
  "status": 404,
  "message": "Book not found"
}
```

---

#### 5. 刪除書籍
**端點：** `DELETE /api/books/{id}`

從系統中刪除一本書籍。需要 **管理員權限** 且需在請求標頭中提供有效的 JWT Token。

**URL 參數：**
| 參數 | 類型 | 說明 |
|------|------|------|
| id | int | 書籍 ID |

**請求標頭：**
```
Authorization: Bearer <JWT_TOKEN>
```

**成功響應 (200)：**
```json
{
  "status": 200,
  "message": "Book deleted successfully"
}
```

**錯誤響應 (404)：**
```json
{
  "status": 404,
  "message": "Book not found"
}
```

---

### API 認證說明

所有需要認證的端點（修改、刪除操作）必須在請求標頭中包含有效的 JWT Token：

```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

**Token 過期處理：**
- Token 有效期：24 小時
- 若 Token 過期，使用者需要重新登入以取得新的 Token
- 錯誤響應 (401)：`{ "status": 401, "message": "Unauthorized. Invalid or expired token." }`

---

### 測試 API

可使用以下工具進行 API 測試：

**使用 cURL：**
```bash
# 登入取得 Token
curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'

# 使用 Token 調用受保護的 API
curl -X GET http://localhost:8080/api/books \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**使用 Postman：**
1. 導入 API 集合或手動創建請求
2. 在 **Authorization** 標籤中選擇 **Bearer Token**
3. 輸入登入取得的 Token
4. 發送請求進行測試