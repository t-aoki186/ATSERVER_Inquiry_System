<!--このファイルはお問い合わせ一覧を表示するものです。
    お問い合わせには個人情報が含まれる可能性があるため、管理者のみがアクセスできる場所に配置してください。
-->

<?php
// 管理用 お問い合わせ一覧ページ
// DB接続情報は環境に合わせて修正してください
$mysqli = new mysqli('Your DB IP address', 'Your DB username', 'Your DB password', 'ats_inquiry_system'); //ご自身のDB情報に変更
if ($mysqli->connect_errno) {
    die('データベース接続失敗: ' . $mysqli->connect_error);
}
$result = $mysqli->query('SELECT * FROM message ORDER BY created_at DESC');
?>
<!DOCTYPE html>
<!--
Developed by
    ___  ___________ __________ _    ____________ 
   /   |/_  __/ ___// ____/ __ \ |  / / ____/ __ \
  / /| | / /  \__ \/ __/ / /_/ / | / / __/ / /_/ /
 / ___ |/ /  ___/ / /___/ _, _/| |/ / /___/ _, _/ 
/_/  |_/_/  /____/_____/_/ |_| |___/_____/_/ |_|

ATSERVER
(c) 2024-2025 ATSERVER (https://x.com/ATSERVER186)
-->
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ATSERVER_お問い合わせシステム | お問い合わせ一覧</title>
    <style>
        body { 
            font-family: 'Noto Sans JP', sans-serif; 
            background: #f7f7f7; }
        table { 
            border-collapse: collapse; 
            width: 95%; 
            margin: 30px auto; 
            background: #fff;
        }
        th, td { 
            border: 1px solid #ccc; 
            padding: 8px 12px; 
        }
        th { 
            background: #e4e4e4; 
        }
        tr:nth-child(even) { 
            background: #f2f2f2; 
        }
        .copy-btn { 
            cursor: pointer; 
            color: #007bff; 
            border: none; 
            background: none; 
        }
    </style>
</head>
<body>
    <h1 style="text-align:center;">お問い合わせ一覧</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>名前</th>
            <th>Twitter ID</th>
            <th>本文</th>
            <th>日時</th>
            <th>コピー</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['twitter_id']) ?></td>
            <td class="msg"><?= nl2br(htmlspecialchars($row['body'])) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
            <td>
                <button class="copy-btn" onclick="copyText(this)">コピー</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <script>
    function copyText(btn) {
        const row = btn.closest('tr');
        const name = row.children[1].innerText;
        const twitter = row.children[2].innerText;
        const body = row.children[3].innerText;
        const text = `名前: ${name}\nTwitter ID: ${twitter}\n本文: ${body}`;
        navigator.clipboard.writeText(text).then(() => {
            btn.innerText = 'コピー済み';
            setTimeout(() => { btn.innerText = 'コピー'; }, 1200);
        });
    }
    </script>
</body>
</html>
<?php
$result->free();
$mysqli->close();
?>