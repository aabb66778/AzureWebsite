<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>測試用</title>
</head>

<body>
    <div>
        <h1>PHP 測試頁面</h1>
        <p>這是一個簡單的 PHP 測試頁面。</p>
    </div>
    <div>
        取得使用者隨機資料(<a href="https://jsonplaceholder.typicode.com/">參考網站</a>)
        <br>
        <ol>
            <li>
                <a href="/userweb" target="_blank">/userweb</a>
            </li>
            <li>
                <a href="/userapi" target="_blank">/userapi</a>
            </li>
        </ol>
    </div>
    <div>
        <?php
        $name = "John";
        $age = 20;
        $city = "New York";
        $data = [
            ["id" => 1, "name" => "Alice", "age" => 25],
            ["id" => 2, "name" => "Bob", "age" => 30],
            ["id" => 3, "name" => "Charlie", "age" => 35],
            ["id" => 4, "name" => "David", "age" => 28],
            ["id" => 5, "name" => "Eve", "age" => 22]
        ];
        // 使用 echo 輸出 HTML
        echo "Hello World!<br>";
        echo "My name is $name and I am $age years old.<br>";
        echo "I live in $city.<br>";

        // 使用 PHP 變數
        echo "<ol style='color:blue;'>";
        for ($i = 0; $i < count($data); $i++) {
            echo "<li>ID: {$data[$i]['id']}, Name: {$data[$i]['name']}, Age: {$data[$i]['age']}</li>";
        }
        echo "</ol>";

        // 使用 PHP 陣列，費式數列，根據使用者輸入決定項數
        ?>
        <form method="POST">
            <label for="count">輸入費式數列的項數：</label>
            <input type="number" id="count" name="count" min="1" max="50" required>
            <button type="submit">生成</button>
        </form>
        <?php
        // 處理表單提交
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['count'])) {
            $count = intval($_POST['count']);
            if ($count > 0 && $count <= 50) {
                $fibonacci = [0, 1];
                for ($i = 2; $i < $count; $i++) {
                    $fibonacci[$i] = $fibonacci[$i - 1] + $fibonacci[$i - 2];
                }
                echo "<div>費式數列的前 {$count} 項為：";
                echo "<ul style='list-style-type:none;'>";
                for ($i = 0; $i < count($fibonacci); $i++) {
                    echo "<li>F({$i}) = {$fibonacci[$i]}</li>";
                }
                echo "</ul>";
                echo "</div>";
            } elseif ($count > 50) {
                echo "<p style='color:red;'>請輸入小於等於 50 的數字。</p>";
            } else {
                echo "<p style='color:red;'>請輸入大於 0 的數字。</p>";
            }
        }
        ?>

    </div>

</body>

</html>