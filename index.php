  <?php
  // フォーム送信時の処理
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $twitter_id = isset($_POST['twitter_id']) ? trim($_POST['twitter_id']) : '';
    $body = isset($_POST['body']) ? trim($_POST['body']) : '';
    $errors = [];
    // reCAPTCHA検証
    $recaptchaSecret = 'Your secret key'; // ←ご自身のシークレットキーに変更
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
    if (empty($recaptchaResponse)) {
      $errors[] = 'reCAPTCHA認証を完了してください。';
    } else {
      $recaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
      $recaptcha = json_decode($recaptcha);
      if (!$recaptcha || !$recaptcha->success) {
        $errors[] = 'reCAPTCHA認証に失敗しました。';
      }
    }
    if ($name === '') {
      $errors[] = '名前を入力してください。';
    }
    if ($body === '') {
      $errors[] = '本文を入力してください。';
    }
    if (empty($errors)) {
      // DB接続
      $mysqli = new mysqli('Your DB IP address', 'Your DB username', 'Your DB password', 'ats_inquiry_system'); //ご自身のDB情報に変更
      if ($mysqli->connect_errno) {
        echo '<div style="color:red;">データベース接続に失敗しました: ' . $mysqli->connect_error . '</div>';
      } else {
        $stmt = $mysqli->prepare('INSERT INTO message (name, twitter_id, body) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $name, $twitter_id, $body);
        if ($stmt->execute()) {
          echo '<div class="container"; style="color:green;">送信が完了しました。お問い合わせありがとうございました。</div>';
        } else {
          echo '<div style="color:red;">送信に失敗しました。</div>';
        }
        $stmt->close();
        $mysqli->close();
      }
    } else {
      foreach ($errors as $e) {
        echo '<div style="color:red;">' . htmlspecialchars($e, ENT_QUOTES, 'UTF-8') . '</div>';
      }
    }
  }
  ?>
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
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>ATSERVER_お問い合わせシステム</title>
<link rel="website icon" type="png" href="https://pic.atserver186.jp/img/ATSERVER/icon.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300&display=swap');

        * {
            margin: 0;
        }


          body{
              font-family: 'Noto Sans JP', sans-serif;
              background-color: #ebebeb;
              padding-top: 60px;
          }


h1 {
  text-align: center;
}

.btn_ATS {
  color: white;
  text-decoration: none; /* リンクの下線を消す */
}

/* ヘッダーここまで */

.link {
  color: black;
  text-decoration: none; /* リンクの下線を消す */
}

.container {
  width: 85%;
  margin: 20px auto; /* 中央配置 */
  padding: 20px;
  background-color: #ffffff; /* 背景色 */
  border-radius: 5px;
}

.form_container{
  width: 85%;
  margin: 20px auto; /* 中央配置 */
  padding: 20px;
  background-color: #ffffff; /* 背景色 */
  border-radius: 5px;
  text-align: center;
}

/* トップページへ戻る */
html {
  scroll-behavior: smooth;
}

.pagetop {
  height: 50px;
  width: 50px;
  position: fixed;
  right: 30px;
  bottom: 30px;
  background: #fff;
  border: solid 2px #000;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 2;
}

.pagetop__arrow {
  height: 10px;
  width: 10px;
  border-top: 3px solid #000;
  border-right: 3px solid #000;
  transform: translateY(20%) rotate(-45deg);
}
/* トップページへ戻る */

/* パンくずリスト */
.breadcrumb-001 {
  display: flex;
  gap: 0 22px;
  list-style: none;
  padding: 0;
  font-size: .9em;
}

.breadcrumb-001 li {
  display: flex;
  align-items: center;
}

.breadcrumb-001 li:first-child::before {
  display: inline-block;
  width: 1em;
  height: 1em;
  margin-right: 4px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M20 20C20 20.5523 19.5523 21 19 21H5C4.44772 21 4 20 4 20V11L1 11L11.3273 1.6115C11.7087 1.26475 12.2913 1.26475 12.6727 1.6115L23 11L20 11V20ZM11 13V19H13V13H11Z' fill='%23333333'%3E%3C/path%3E%3C/svg%3E");
  background-repeat: no-repeat;
  content: '';
}

.breadcrumb-001 li:not(:last-child)::after {
  display: inline-block;
  transform: rotate(45deg);
  width: .3em;
  height: .3em;
  margin-left: 10px;
  border-top: 1px solid #333333;
  border-right: 1px solid #333333;
  content: '';
}

.breadcrumb-001 a {
  color: #333333;
  text-decoration: none;
}
/* パンくずリスト */

/* サービス一覧リンク */
.link_btn_list {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 20px;
  padding: 20px 0;
}

.link_btn_list_item {
  background: #E4E4E4;
  color: #000;
  width: 48%;
  max-width: 300px;
  flex: 0 0 auto;
  /*border-radius: 10px;*//*角丸め*/
  /*box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);*//*影*/
  padding: 15px;
  transition: 0.3s;
}

.link_btn_list_item img {
  width: 100%;
  border-radius: 10px;
}

.link_btn_list_item h3 {
  font-size: 1.2rem;
  margin: 10px 0;
}

.link_btn_list_item p {
  font-size: 0.9rem;
  margin-bottom: 10px;
}

.link_btn_list_item:hover {
  transform: scale(1.05);
  opacity: 0.9;
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
  .link_btn_list {
    flex-direction: column;
    align-items: center;
  }

  .link_btn_list_item {
    width: 100%;
    max-width: 90%;
  }
}

/* ハンバーガーメニュー */
.menu-toggle {
  font-size: 1.5em;
  background: none;
  border: none;
  color: white;
  cursor: pointer;
  display: none; /* デフォルトでは非表示 */
  margin-left: auto; /* 右寄せ */
}

.nav-menu {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  width: 100%;
}

.nav-menu ul {
  list-style: none;
  display: flex;
  gap: 20px;
  padding: 0;
}

.nav-menu a {
  text-decoration: none;
  color: white;
}

/* 画面幅が768px以下になったらメニューを隠し、ハンバーガーを表示 */
@media (max-width: 768px) {
  .menu-toggle {
    display: block;
    position: absolute;
    right: 20px; /* 右端に配置 */
    top: 50%;
    transform: translateY(-50%);
  }

  .nav-menu {
    display: none;
    flex-direction: column;
    position: absolute;
    top: 50px;
    left: 0;
    width: 100%;
    background: #333;
  }

  .nav-menu ul {
    flex-direction: column;
    text-align: center;
    padding: 0;
  }

  .nav-menu a {
    display: block;
    padding: 10px;
    color: white;
  }

  .nav-menu.show {
    display: flex;
  }
}

/* フッター */
        footer{
            width: 100%;
            left: 0px;
            color: white;
            background-color: #202124;
            text-align: center;
            padding: 10px 0;
        }

        .MutualLink{
          color: #53ACFF;
        }

</style>
</head>
<body>

  <div class="container">
    <!--パンくずリスト-->
    <ol class="breadcrumb-001">
      <li><a href="../">ホーム</a></li>
      <li>お問い合わせ</li>
    </ol>
    <!--パンくずリスト 参考:https://pote-chil.com/css-stock/ja/breadcrumb-->
  <h1>お問い合わせフォーム</h1>
  <form class="form_container" method="post" action="">
    <label for="name">名前:</label><br>
    <input type="text" id="name" name="name" required><br>
    <label for="twitter_id">Twitter ID（任意）:</label><br><!--Twitter以外の連絡手段に変更してもOK メールアドレス等に変更する場合はセキュリティ設定をしっかりすること-->
    <input type="text" id="twitter_id" name="twitter_id" placeholder="@example"><br>
    <label for="body">本文:</label><br>
    <textarea id="body" name="body" required></textarea><br><br>
    <div style="text-align:center;">
      <div class="g-recaptcha" data-sitekey="Your site key"></div> <!-- ←ご自身のサイトキーに変更 -->
    </div>
    <br>
    <input type="submit" value="送信">
    <br>
    <hr>
    <p>
      このページはスパム対策のため、reCAPTCHAを使用しています。<br>
      reCAPTCHAの利用規約とプライバシーポリシーに同意の上、送信してください。<br>
      reCAPTCHAを送信せずにお問い合わせを送信すると、お問い合わせ内容がすべて消えるのでご注意ください。
    </p>
  </form>
  <br>
  <br>
  Developed by ATSERVER
</div><!--containerのdiv-->
<hr>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>