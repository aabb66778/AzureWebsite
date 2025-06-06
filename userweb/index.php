

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JavaScript API 請求範例</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .content {
            padding: 40px;
        }

        .method-selector {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .method-selector label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: #495057;
        }

        .method-selector select {
            width: 100%;
            padding: 10px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            font-size: 16px;
        }

        .control-panel {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            border: 1px solid #e9ecef;
        }

        .card h3 {
            color: #495057;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: #6c757d;
            font-weight: 500;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            outline: none;
            border-color: #4facfe;
        }

        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: transform 0.2s, box-shadow 0.2s;
            width: 100%;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            color: #333;
        }

        .result-section {
            margin-top: 30px;
        }

        .result-section h3 {
            color: #495057;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .result-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            min-height: 200px;
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            overflow-x: auto;
            font-size: 14px;
            line-height: 1.5;
        }

        .loading {
            text-align: center;
            color: #6c757d;
            font-style: italic;
        }

        .error {
            color: #dc3545;
            background: #f8d7da;
            border-color: #f5c6cb;
        }

        .success {
            color: #155724;
            background: #d4edda;
            border-color: #c3e6cb;
        }

        .api-info {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .code-example {
            background: #2d3748;
            color: #e2e8f0;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            overflow-x: auto;
        }

        .code-example h4 {
            color: #4facfe;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            
            .content {
                padding: 20px;
            }
            
            .control-panel {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 JavaScript API 請求範例</h1>
            <p>直接從瀏覽器請求 JSONPlaceholder API</p>
        </div>
        
        <div class="content">
            <div class="api-info">
                <h4>📡 API 資訊</h4>
                <p><strong>基礎 URL:</strong> https://jsonplaceholder.typicode.com</p>
                <p><strong>單一端點:</strong> /todos/{id} - 獲取特定 TODO</p>
                <p><strong>全部端點:</strong> /todos - 獲取所有 TODO</p>
                <p><strong>範例回應:</strong> {"userId": 1, "id": 1, "title": "delectus aut autem", "completed": false}</p>
            </div>

            <div class="api-info">
                <h4>🌐 請求功能</h4>
                <p><strong>🎯 單一請求:</strong> 獲取指定 ID 的 TODO 項目</p>
                <p><strong>📋 批量請求:</strong> 同時獲取多個連續 ID 的 TODO 項目</p>
                <p><strong>🌐 全部請求:</strong> 獲取完整的 200 個 TODO 項目</p>
                <p><strong>⚡ 並行處理:</strong> 批量請求使用 Promise.all 同時處理</p>
            </div>

            <div class="control-panel">
                <div class="card">
                    <h3>🎯 獲取單一 TODO</h3>
                    <div class="input-group">
                        <label for="todoId">TODO ID (1-200):</label>
                        <input type="number" id="todoId" min="1" max="200" value="1" placeholder="輸入 TODO ID">
                    </div>
                    <button class="btn" onclick="fetchSingleTodo()">獲取 TODO</button>
                </div>

                <div class="card">
                    <h3>📋 批量獲取 TODO</h3>
                    <div class="input-group">
                        <label for="batchCount">取得筆數 (1-200):</label>
                        <input type="number" id="batchCount" min="1" max="200" value="10" placeholder="輸入要取得的筆數">
                    </div>
                    <div class="input-group">
                        <label for="startId">起始 ID (1-200):</label>
                        <input type="number" id="startId" min="1" max="200" value="1" placeholder="輸入起始 ID">
                    </div>
                    <button class="btn btn-secondary" onclick="fetchBatchTodos()">批量獲取 TODO</button>
                </div>

                <div class="card">
                    <h3>🌐 獲取所有 TODO</h3>
                    <p style="color: #6c757d; margin-bottom: 15px;">獲取完整的 200 個 TODO 項目</p>
                    <button class="btn" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);" onclick="fetchAllTodos()">獲取所有 TODO</button>
                </div>
            </div>

            <div class="code-example">
                <h4>💻 JavaScript Fetch API 範例程式碼:</h4>
                <pre><code>// 單一請求
const url = "https://jsonplaceholder.typicode.com/todos/1";
fetch(url).then(response => response.json()).then(data => console.log(data));

// 批量請求 (同時請求多個 ID)
const todoIds = [1, 2, 3, 4, 5];
const promises = todoIds.map(id => 
    fetch(`https://jsonplaceholder.typicode.com/todos/${id}`)
        .then(response => response.json())
);

Promise.all(promises)
    .then(results => {
        console.log('批量結果:', results);
    })
    .catch(error => {
        console.error('錯誤:', error);
    });</code></pre>
            </div>

            <div class="result-section">
                <h3>📊 API 回應結果</h3>
                <div id="result" class="result-box">
                    選擇請求方法並點擊按鈕開始測試...
                </div>
            </div>
        </div>
    </div>

    <script>
        function showLoading() {
            document.getElementById('result').innerHTML = '⏳ 載入中...';
            document.getElementById('result').className = 'result-box loading';
        }

        function showResult(data, isError = false) {
            const resultDiv = document.getElementById('result');
            resultDiv.className = isError ? 'result-box error' : 'result-box success';
            
            if (typeof data === 'object') {
                resultDiv.innerHTML = JSON.stringify(data, null, 2);
            } else {
                resultDiv.innerHTML = data;
            }
        }

        function fetchSingleTodo() {
            const todoId = document.getElementById('todoId').value;
            
            if (!todoId || todoId < 1 || todoId > 200) {
                showResult('❌ 請輸入有效的 TODO ID (1-200)', true);
                return;
            }

            showLoading();

            const url = `https://jsonplaceholder.typicode.com/todos/${todoId}`;

            fetch(url, {
                method: 'GET'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                showResult(data);
            })
            .catch(error => {
                showResult('❌ 網路錯誤: ' + error.message, true);
            });
        }

        function fetchBatchTodos() {
            const batchCount = parseInt(document.getElementById('batchCount').value);
            const startId = parseInt(document.getElementById('startId').value);
            
            if (!batchCount || batchCount < 1 || batchCount > 200) {
                showResult('❌ 請輸入有效的取得筆數 (1-200)', true);
                return;
            }
            
            if (!startId || startId < 1 || startId > 200) {
                showResult('❌ 請輸入有效的起始 ID (1-200)', true);
                return;
            }
            
            if (startId + batchCount - 1 > 200) {
                showResult('❌ 起始 ID + 取得筆數不能超過 200', true);
                return;
            }

            showLoading();

            // 建立要請求的 ID 陣列
            const todoIds = [];
            for (let i = startId; i < startId + batchCount; i++) {
                todoIds.push(i);
            }

            // 同時發送多個請求
            const promises = todoIds.map(id => {
                const url = `https://jsonplaceholder.typicode.com/todos/${id}`;
                return fetch(url, { method: 'GET' })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status} for ID ${id}`);
                        }
                        return response.json();
                    })
                    .then(data => ({ ...data, requestId: id }))
                    .catch(error => ({ 
                        error: error.message, 
                        requestId: id 
                    }));
            });

            Promise.all(promises)
                .then(results => {
                    // 過濾出成功的結果
                    const successResults = results.filter(result => !result.error);
                    const errorResults = results.filter(result => result.error);
                    
                    let displayData = {
                        summary: {
                            total_requested: batchCount,
                            successful: successResults.length,
                            failed: errorResults.length,
                            start_id: startId,
                            end_id: startId + batchCount - 1
                        },
                        data: successResults
                    };
                    
                    if (errorResults.length > 0) {
                        displayData.errors = errorResults;
                    }
                    
                    showResult(displayData);
                })
                .catch(error => {
                    showResult('❌ 批量請求錯誤: ' + error.message, true);
                });
        }

        function fetchAllTodos() {
            showLoading();

            const url = `https://jsonplaceholder.typicode.com/todos`;

            fetch(url, {
                method: 'GET'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                const displayData = {
                    summary: {
                        total_count: data.length,
                        message: "完整的 TODO 列表"
                    },
                    data: data
                };
                showResult(displayData);
            })
            .catch(error => {
                showResult('❌ 網路錯誤: ' + error.message, true);
            });
        }

        // 頁面載入時自動獲取第一個 TODO 作為示例
        window.addEventListener('load', function() {
            setTimeout(fetchSingleTodo, 1000);
        });
    </script>
</body>
</html>
