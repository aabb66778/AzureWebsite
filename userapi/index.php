<?php
// 設定回應為 JSON 格式
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 處理 OPTIONS 請求 (CORS 預檢)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// API 函數：使用 file_get_contents 請求 JSONPlaceholder API
function fetchFromJsonPlaceholder($endpoint) {
    $url = "https://jsonplaceholder.typicode.com" . $endpoint;
    
    // 建立 context 選項，包含 SSL 設定
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 30,
            'user_agent' => 'Mozilla/5.0 (compatible; PHP API Proxy)',
            'ignore_errors' => true
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ]);
    
    // 檢查是否支援 HTTPS
    if (!in_array('https', stream_get_wrappers())) {
        // 如果不支援 HTTPS，嘗試使用 HTTP
        $url = str_replace('https://', 'http://', $url);
    }
    
    // 執行請求
    $response = file_get_contents($url, false, $context);
    
    if ($response === false) {
        $error = error_get_last();
        return [
            'success' => false,
            'error' => 'file_get_contents 請求失敗: ' . ($error['message'] ?? '未知錯誤'),
            'url' => $url,
            'https_supported' => in_array('https', stream_get_wrappers()) ? 'Yes' : 'No',
            'openssl_loaded' => extension_loaded('openssl') ? 'Yes' : 'No'
        ];
    }
    
    // 獲取 HTTP 狀態碼
    $httpCode = 200;
    if (isset($http_response_header)) {
        foreach ($http_response_header as $header) {
            if (preg_match('/^HTTP\/\d\.\d\s+(\d+)/', $header, $matches)) {
                $httpCode = intval($matches[1]);
                break;
            }
        }
    }
    
    if ($httpCode !== 200) {
        return [
            'success' => false,
            'error' => 'HTTP 錯誤: ' . $httpCode,
            'http_code' => $httpCode
        ];
    }
    
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            'success' => false,
            'error' => 'JSON 解析錯誤: ' . json_last_error_msg()
        ];
    }
    
    return [
        'success' => true,
        'data' => $data,
        'http_code' => $httpCode
    ];
}

// 批量請求函數
function fetchBatchTodos($startId, $count) {
    $results = [];
    $errors = [];
    
    for ($i = $startId; $i < $startId + $count; $i++) {
        $result = fetchFromJsonPlaceholder("/todos/{$i}");
        
        if ($result['success']) {
            $results[] = $result['data'];
        } else {
            $errors[] = [
                'id' => $i,
                'error' => $result['error']
            ];
        }
    }
    
    return [
        'success' => true,
        'summary' => [
            'total_requested' => $count,
            'successful' => count($results),
            'failed' => count($errors),
            'start_id' => $startId,
            'end_id' => $startId + $count - 1
        ],
        'data' => $results,
        'errors' => $errors
    ];
}

// 路由處理
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'single':
            $todoId = intval($_GET['id'] ?? 1);
            
            if ($todoId < 1 || $todoId > 200) {
                throw new Exception('TODO ID 必須在 1-200 之間');
            }
            
            $result = fetchFromJsonPlaceholder("/todos/{$todoId}");
            echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
            
        case 'batch':
            $startId = intval($_GET['start'] ?? 1);
            $count = intval($_GET['count'] ?? 10);
            
            if ($startId < 1 || $startId > 200) {
                throw new Exception('起始 ID 必須在 1-200 之間');
            }
            
            if ($count < 1 || $count > 200) {
                throw new Exception('取得筆數必須在 1-200 之間');
            }
            
            if ($startId + $count - 1 > 200) {
                throw new Exception('起始 ID + 取得筆數不能超過 200');
            }
            
            $result = fetchBatchTodos($startId, $count);
            echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
            
        case 'all':
            $result = fetchFromJsonPlaceholder("/todos");
            
            if ($result['success']) {
                $response = [
                    'success' => true,
                    'summary' => [
                        'total_count' => count($result['data']),
                        'message' => '完整的 TODO 列表'
                    ],
                    'data' => $result['data']
                ];
                echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            break;
            
        case 'info':
            $info = [
                'success' => true,
                'api_info' => [
                    'name' => 'JSONPlaceholder API 代理服務',
                    'version' => '1.0.0',
                    'method' => 'file_get_contents',
                    'base_url' => 'https://jsonplaceholder.typicode.com',
                    'endpoints' => [
                        'single' => '?action=single&id=1',
                        'batch' => '?action=batch&start=1&count=10',
                        'all' => '?action=all',
                        'info' => '?action=info'
                    ]
                ],
                'sample_response' => [
                    'userId' => 1,
                    'id' => 1,
                    'title' => 'delectus aut autem',
                    'completed' => false
                ]
            ];
            echo json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
            
        default:
            $welcome = [
                'success' => true,
                'message' => '歡迎使用 JSONPlaceholder API 代理服務',
                'method' => 'file_get_contents (無需 cURL 擴展)',
                'usage' => [
                    'single_todo' => '?action=single&id=1',
                    'batch_todos' => '?action=batch&start=1&count=10',
                    'all_todos' => '?action=all',
                    'api_info' => '?action=info'
                ],
                'examples' => [
                    '獲取 ID 為 1 的 TODO' => $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '?action=single&id=1',
                    '獲取 ID 1-10 的 TODO' => $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '?action=batch&start=1&count=10',
                    '獲取所有 TODO' => $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '?action=all'
                ],
                'timestamp' => date('Y-m-d H:i:s'),
                'server_info' => [
                    'php_version' => PHP_VERSION,
                    'curl_available' => function_exists('curl_init') ? 'Yes' : 'No',
                    'https_wrapper' => in_array('https', stream_get_wrappers()) ? 'Yes' : 'No',
                    'openssl_loaded' => extension_loaded('openssl') ? 'Yes' : 'No',
                    'allow_url_fopen' => ini_get('allow_url_fopen') ? 'Yes' : 'No'
                ]
            ];
            echo json_encode($welcome, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
    }
    
} catch (Exception $e) {
    http_response_code(400);
    $error = [
        'success' => false,
        'error' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ];
    echo json_encode($error, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>