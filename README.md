---
page_type: sample
languages:
- php
products:
- azure
description: "This sample demonstrates a tiny Hello World PHP app for App Service."
urlFragment: php-docs-hello-world
---

# PHP Hello World

This sample demonstrates a tiny Hello World PHP app for [App Service](https://docs.microsoft.com/azure/app-service).

## Contributing

This project has adopted the [Microsoft Open Source Code of Conduct](https://opensource.microsoft.com/codeofconduct/). For more information see the [Code of Conduct FAQ](https://opensource.microsoft.com/codeofconduct/faq/) or contact [opencode@microsoft.com](mailto:opencode@microsoft.com) with any additional questions or comments.

# 🚀 簡易 REQUEST 網站

這是一個用來測試 [JSONPlaceholder API](https://jsonplaceholder.typicode.com) 的簡易網站。

## 📋 功能特色

- **現代化 UI 設計** - 響應式設計，支援桌面和行動裝置
- **即時 API 測試** - 直接在瀏覽器中測試 API 請求
- **JSON 格式化顯示** - 美化的 JSON 回應格式
- **錯誤處理** - 完整的錯誤處理和使用者回饋
- **載入狀態** - 清楚的載入指示器

## 🎯 主要功能

### 1. 獲取單一 TODO
- 輸入 TODO ID (1-200)
- 獲取特定的 TODO 項目資料
- 顯示 JSON 格式的回應

### 2. 獲取所有 TODO
- 一鍵獲取所有 200 個 TODO 項目
- 完整的資料列表顯示

## 🔧 技術架構

- **前端**: HTML5, CSS3, JavaScript (ES6+)
- **後端**: PHP
- **API**: JSONPlaceholder REST API
- **樣式**: 現代化 CSS Grid 和 Flexbox 佈局

## 🚀 使用方法

1. 確保您的伺服器支援 PHP
2. 將檔案放置在 Web 伺服器目錄中
3. 在瀏覽器中開啟 `index.php`
4. 開始測試 API 請求！

## 📡 API 資訊

**基礎 URL**: https://jsonplaceholder.typicode.com

**測試端點**: 
- `/todos/{id}` - 獲取特定 TODO 項目
- `/todos` - 獲取所有 TODO 項目

**範例回應**:
```json
{
  "userId": 1,
  "id": 1,
  "title": "delectus aut autem",
  "completed": false
}
```

## 🎨 特色

- 漸層背景設計
- 卡片式佈局
- 動畫效果和互動回饋
- 響應式設計
- 清楚的視覺狀態指示

## 📱 響應式支援

網站完全支援各種螢幕尺寸：
- 桌面電腦 (1200px+)
- 平板電腦 (768px - 1199px)
- 手機 (< 768px)

---

立即開始測試您的 API 請求！🎉
