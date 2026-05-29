# 書籍搜尋過濾功能實現 - 變更總結

## 實現概述
已成功實現書籍搜尋過濾功能，支持按書籍名稱和作者名稱進行模糊查詢。

## 修改的文件

### 後端 (PHP)

#### 1. `app/DTO/Book/BookQueryDTO.php`
- **添加字段**：`$title`（書籍標題）、`$search`（通用搜尋）
- **作用**：擴展查詢參數支持，允許前端傳遞搜尋條件

#### 2. `app/Models/BookModel.php`
- **修改方法 `filterAuthorName()`**：從精確匹配改為模糊查詢（LIKE）
- **添加方法 `filterTitle()`**：支持按書籍標題模糊查詢
- **添加方法 `filterSearch()`**：通用搜尋方法，同時搜尋書籍標題和作者名稱（使用 OR 條件）
- **保留方法 `filterSlug()`**：保持原有功能不變

#### 3. `app/Services/BookService.php`
- **更新 `getBooks()` 方法**：
  - 在 cache key 中添加新的搜尋參數（`title`、`search`）
  - 在模型查詢鏈中添加調用 `filterTitle()` 和 `filterSearch()` 方法
  - 確保搜尋參數被正確應用到數據庫查詢

### 前端 (JavaScript/HTML)

#### 4. `app/Views/books/display.php`
- **添加搜尋欄 UI**：
  - 搜尋輸入框（id: `searchInput`）
  - 搜尋按鈕（id: `searchBtn`）
  - 清除搜尋按鈕（id: `clearSearchBtn`）
  - 使用 Bootstrap 的 `input-group` 樣式

#### 5. `public/js/book-display.js`
- **添加屬性**：`searchQuery` - 存儲當前搜尋查詢字符串
- **添加方法**：
  - `performSearch()` - 執行搜尋並重置分頁
  - `clearSearch()` - 清除搜尋並重新加載所有書籍
- **修改方法**：
  - `setupEventListeners()` - 添加搜尋輸入框的事件監聽（按下 Enter 鍵或點擊搜尋按鈕）
  - `loadBooks()` - 傳遞 `searchQuery` 參數到 API
- **功能特性**：
  - 支持 Enter 鍵快速搜尋
  - 搜尋時自動重置到第一頁
  - 搜尋結果保持原有的視圖模式（網格或列表）

#### 6. `public/js/api.js`
- **修改方法 `getBooks()`**：
  - 添加第三個參數 `search`
  - 當 `search` 不為空時，添加到 GET 請求參數中
  - 保持向下相容性（不傳遞搜尋參數時不受影響）

## API 端點

### GET /api/books
支持的查詢參數：
- `page` - 頁碼
- `perPage` - 每頁數量
- `search` - 通用搜尋（搜尋書籍標題和作者名稱）
- `title` - 精確搜尋書籍標題
- `authorName` - 精確搜尋作者名稱
- `slug` - 搜尋書籍 slug
- `sort` - 排序字段
- `direction` - 排序方向

**範例**：
```
GET /api/books?page=1&perPage=10&search=PHP
GET /api/books?page=1&perPage=10&title=Python
GET /api/books?page=1&perPage=10&authorName=Knuth
```

## 技術特性

1. **模糊查詢**：使用 SQL LIKE 操作符實現模糊匹配
2. **組合查詢**：`search` 參數使用 OR 條件同時搜尋書名和作者
3. **快取支持**：搜尋參數包含在快取鍵中，確保快取的一致性
4. **分頁重置**：搜尋時自動重置到第一頁
5. **向下相容**：所有修改都保持向後相容性

## 測試清單

- [ ] 搜尋書籍標題
- [ ] 搜尋作者名稱
- [ ] 使用通用搜尋欄
- [ ] 按 Enter 鍵快速搜尋
- [ ] 點擊搜尋按鈕
- [ ] 點擊清除按鈕恢復到全部書籍
- [ ] 搜尋結果分頁功能
- [ ] 在網格和列表視圖之間切換
- [ ] 搜尋為空時顯示所有書籍
- [ ] 搜尋結果為空時顯示提示信息

## 安裝和部署

1. 所有文件已更新，無需額外的數據庫遷移
2. 無需安裝新的依賴包
3. 代碼已完全向下相容，不會影響現有功能
4. 可直接部署到生產環境
