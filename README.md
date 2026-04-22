markdown_content = """# BookStore API Service

這是一個基於 **CodeIgniter 4** 框架開發的 RESTful API 專案，書籍管理系統。專案中導入了 **JWT (JSON Web Token)** 身份驗證機制，並利用 **Redis** 優化頻繁讀取的數據查詢。

## 技術棧
* **Backend:** PHP 8.x / CodeIgniter 4 [cite: 55, 67]
* **Database:** MySQL 8.x [cite: 67]
* **Caching:** Redis [cite: 54]
* **Authentication:** JWT (Firebase/php-jwt) [cite: 56]
* **Tools:** Composer, Docker [cite: 55, 79]

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
## API 文件概覽
本專案目前提供以下核心 API 節點，可配合 Postman 進行測試 ：
* POST /api/login - 使用者登入並取得 Token。
* GET /api/books - 取得所有書籍資訊（支援 Redis 快取讀取）。
* POST /api/books - 新增書籍（具備管理員權限驗證）。
* PUT /api/books/{id} - 編輯書籍（具備管理員權限驗證）。
* DELETE /api/books/{id} - 刪除書籍（具備管理員權限驗證）。