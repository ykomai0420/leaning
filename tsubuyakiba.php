<?php
  define('DB_USER', '○○○○');
  define('DB_PASSWD', '○○○○');
  define('DB_HOST', '○○○○');
  define('DB_NAME', '○○○○');
  define('HTML_CHARCTER_SET', 'UTF-8');
  define('DB_CHARACTER_SET', 'UTF8');

  $bbs_data = array();
  $error = array();
  $name = '';
  $comment = '';

  $link = get_db_connect();
  if ($link) {
    if (isset($_POST['comment']) === TRUE) {
      value_check('name', 20, '名前');
      value_check('comment', 100, 'つぶやき');
      if (empty($error) === TRUE) {
        put_db($link);
      }
    }
    if (empty($error) === TRUE) {
      $bbs_data = get_db($link);
    }
  }
  function value_check($target, $num, $display) {
    global $error;
    if (isset($_POST[$target]) === TRUE) {
      if (mb_strlen($_POST[$target]) === 0) {
        $error[] = $display.'が入力されていません。';
      }
      if (mb_strlen($_POST[$target]) > $num) {
        $error[] = $display.'は'.$num.'文字以内で入力してください';
      }
    } else {
      $error[] = $display.'が入力されていません';
    }
  }
  function get_db_connect() {
    if ($link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME)) {
      return $link;
    } else {
      return false;
    }
  }
  function put_db($link) {
    $created_at = date('Y-m-d H:i:s');
    $query = "INSERT INTO bbs_table(name, comment, created_at) VALUES
    ('".$_POST['name']."','".$_POST['comment']."','".$created_at."')";
    $result = mysqli_query($link, $query);
    return $result;
  }
  function get_db($link) {
    $query = "SELECT name, comment, created_at FROM bbs_table";
    $result = mysqli_query($link, $query);
    while ($row = mysqli_fetch_array($result)) {
      $bbs_data[] = $row;
    }
    mysqli_free_result($result);
    return $bbs_data;
  }
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <title>TSUBUYAKIBA</title>
    <link rel="stylesheet" href="tsubuyakiba_pc.css">
  </head>
  <body>
    <header>
      <h1>TSUBUYAKIBA</h1>
    </header>
    <article>
      <div>
        <ul>
          <?php foreach ($error as $value) { ?>
              <li>
          <?php print htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>
          </li>
          <?php } ?>
        </ul>
      </div>
      <div>
        <form method="post">
          <input type="text" name="name" placeholder="ユーザー名"><br/>
          <input type="text" name="comment" placeholder="つぶやき"><br/>
          <input type="submit" value="つぶやく">
        </form>
      </div>
      <div class="tag">
        <p>▼思ったことをつぶやこう▼</p>
      </div>
      <div class="bbs">
        <ul>
        <?php foreach ((array)$bbs_data as $value) { ?>
          <li>
          <?php print htmlspecialchars($value['created_at'], ENT_QUOTES, 'UTF-8'); ?>
          <?php print htmlspecialchars($value['name'], ENT_QUOTES, 'UTF-8'); ?><br/>
          <?php print htmlspecialchars($value['comment'], ENT_QUOTES, 'UTF-8'); ?>
          </li>
        <?php } ?>
        </ul>
      </div>
    </article>
  </body>
