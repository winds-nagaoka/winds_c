<?php
  // 初期設定
  date_default_timezone_set('Asia/Tokyo');
	$selfname = basename($_SERVER['SCRIPT_NAME']);

  if($_SERVER['REQUEST_METHOD'] == "GET") {
    header("Location: ./#contact");
    exit();
  }
  if($_SERVER['REQUEST_METHOD'] == "POST") {
    $input['name'] = $_POST['name'];
    $original['name'] = htmlspecialchars($_POST['name']);
    $input['email'] = $_POST['email'];
    $original['email'] = htmlspecialchars($_POST['email']);
    $input['message'] = $_POST['message'];
    $original['message'] = htmlspecialchars($input['message']);
    $input['message'] = htmlspecialchars($input['message']);
    $input['message'] = str_replace("\r\n", "<br>", $input['message']);
    $input['message'] = str_replace("\n", "<br>", $input['message']);
    $input['message'] = str_replace("\r", "<br>", $input['message']);
    $input['message'] = str_replace("\t", "　　　　", $input['message']);
  }
  if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['modify'])) {
    $mode = 'modify';
  }elseif($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['confirm'])){
    $mode = 'confirm';

    $mail['time'] = date('Y/m/d H:i');
    $mail['expire'] = date('Y/m/d H:i', strtotime('+1 week'));

    //お客様へ受付メール送信
		$mail['address'] = mb_encode_mimeheader($original['name']).'<'.$original['email'].'>';
		$mail['subject'] = "【ウィンズより】お問い合わせを受け付けました";
		$mail['message'] = $original['name']." 様\r\n\r\nお問い合わせありがとうございます。\r\n\r\n"
											."ザ・ウィンド・アンサンブル(winds-n.com)にて、\r\n下記のお問い合わせを受け付けました。\r\nお返事をお送りさせていただくまで、もうしばらくお待ちください。\r\n\r\n"
											."なお、一週間以上経過しても返信がない場合には、\r\nこちらで正常に受け付けられていない可能性がありますので、\r\nお手数ですが改めてご連絡をいただけますようお願いいたします。\r\n\r\n--\r\n\r\n"
											."お名前:\r\n".$original['name']."\r\n\r\nアドレス:\r\n".$original['email']."\r\n\r\nメッセージ:\r\n".$original['message'];
		$mail['additional_parameter'] = "From: ".mb_encode_mimeheader('ザ・ウィンド・アンサンブル')."<contact@winds-n.com>\r\n";
		$mail['additional_parameter'] .= "Return-Path: contact@winds-n.com";
		$result['send'] = mb_send_mail($mail['address'],$mail['subject'],$mail['message'],$mail['additional_parameter']);
		$result['sendstatus'] = $result['send'] == 1 ? '送信成功' : '送信失敗';
    $mode = $result['send'] == 1 ? 'confirm' : 'error';
    if($mode == 'error' && isset($_POST['errorcount'])) {
      setcookie('MESSAGE', '8', time()+60, '/', '192.168.1.2', false, true);
      header("Location: ./");
      exit();
    }
		//お問い合わせお知らせメール送信
		$mail['address'] = "winds.nagaoka@gmail.com";
		$mail['subject'] = "【winds-n.com】PCサイトにてお問い合わせがありました";
		$mail['message'] = "\r\nwinds-n.com (PC) にて下記のお問い合わせがありました。\r\n\r\n受付日時: ".$mail['time']."\r\n返信目安: ".$mail['expire']."(一週間)\r\n\r\nアドレス確認: ".$result['sendstatus']."\r\n\r\n--\r\n\r\nお名前: ".$original['name']."\r\n\r\nアドレス: ".$original['email']."\r\n\r\nメッセージ:\r\n".$original['message'];
		$mail['additional_parameter'] = "From: ".mb_encode_mimeheader('お問い合わせ')."<contact@winds-n.com>\r\n";
		$mail['additional_parameter'] .= "Return-Path: contact@winds-n.com";
    // echo '内容'.'<pre>';
    // echo 'To: '.$mail['address'].'<br>';
    // echo 'Subject: '.$mail['subject'].'<br>';
    // echo 'Message:<br>'.$mail['message'].'<br>';
    // echo 'Add: '.$mail['additional_parameter'].'<br>';
    // exit();
		mb_send_mail($mail['address'],$mail['subject'],$mail['message'],$mail['additional_parameter']);

  }else{
    $mode = 'check';
  }
?><html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1.0">
  <title>お問い合わせ｜ザ・ウィンド・アンサンブル</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/contact.css">
  <link rel="shortcut icon" href="image/favicon.ico">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
  <script>
    ((d) => {
      var config = { kitId: 'wxw0kpr', scriptTimeout: 3000, async: true },
      h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
    })(document)
  </script>
</head>
<body>

  <header id='top'>
    <section id="main-logo">
      <a href='index.php'>
        <?php require('image/logo/logo.svg'); ?>
      </a>
    </section>
  </header>

  <div class='top-title'>
    <div>
      <h1 data-subttl="Contact">お問い合わせ</h1>
      <div class='bread'><a href='index.php'>ホーム</a><i class="fas fa-chevron-right"></i><a href='contact.php'>お問い合わせ</a></div>
    </div>
  </div>


  <div class='block contact' id="contact">
    <div class='contents'>
<?php if($mode == 'check') { ?>
      <div class='text'>
        <p>入力内容および<a href="policy" target="_blank">プライバシーポリシー<i class="fa fa-external-link" aria-hidden="true"></i></a>をご確認ください。</p>
      </div>
      <div class="input">
        <label>名前</label><span><?php echo $input['name']; ?></span>
        <label>メールアドレス</label><span><?php echo $input['email']; ?></span>
        <label>お問い合わせ内容</label><span><?php echo $input['message']; ?></span>
      </div>
      <form method="post" action="<?php echo $selfname; ?>">
        <?php if(isset($_POST['errorcount'])) { echo '<input type="hidden" name="errorcount" value="1">'; } ?>
        <input type="hidden" name="name" value="<?php echo $_POST['name']; ?>">
        <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
        <input type="hidden" name="message" value="<?php echo $_POST['message']; ?>">
        <div class="message"><p>この内容でよろしければ、送信ボタンを押してください。</p></div>
        <div class='button'>
          <button type="submit" name="modify" class="send-button" value="modify">修正する</button>
          <button type="submit" name="confirm" class="send-button confirm" value="confirm">送信</button>
        </div>
      </form>
<?php }elseif($mode == 'modify') { ?>
      <form method="post" action="<?php echo $selfname; ?>" id="contact">
        <label>お名前</label><input type="text" name="name" value="<?php echo $_POST['name']; ?>" class="name" required>
        <label>メールアドレス</label><input type="address" autocorrect="off" autocapitalize="off" name="email" value="<?php echo $_POST['email']; ?>" class="email" required>
        <label>お問い合わせ内容</label><textarea name="message" class="text" required><?php echo $_POST['message']; ?></textarea>
        <button type="submit" name="send" class="sendbutton" value="send">確認</button>
      </form>
<?php }elseif($mode == 'error') { ?>
      <div class='error'>
        <p style="color:#B60005;">
          メールアドレスが確認できません。<br>
          有効なアドレスを入力してください。
        </p>
      </div>
      <form method="post" action="<?php echo $selfname; ?>" id="contact">
        <input type="hidden" name="errorcount" value="1">
        <label>お名前</label><input type="text" name="name" value="<?php echo $_POST['name']; ?>" class="name" required>
        <label>メールアドレス</label><input type="address" autocorrect="off" autocapitalize="off" name="email" value="<?php echo $_POST['email']; ?>" class="email" required>
        <label>お問い合わせ内容</label><textarea name="message" class="text" required><?php echo $_POST['message']; ?></textarea>
        <button type="submit" name="send" class="sendbutton" value="send">確認</button>
      </form>
<?php }elseif($mode == 'confirm') { ?>
      <div class='text'>
        <p>下記のお問い合わせを受け付けました。お返事をお送りさせていただくまで、しばらくお待ちください。</p>
        <p>なお、一週間以上経過しても返信がない場合にはこちらで正常に受け付けられていない可能性がありますので、お手数ですが改めてご連絡をいただけますようお願いいたします。</p>
      </div>
      <div class="input">
        <label>名前</label><span><?php echo $input['name']; ?></span>
        <label>メールアドレス</label><span><?php echo $input['email']; ?></span>
        <label>お問い合わせ内容</label><span><?php echo $input['message']; ?></span>
      </div>
<?php } ?>
    </div>
  </div>

  <div class='block back-to-home'>
    <div>
      <a href='index.php'>ホームへ戻る</a>
    </div>
  </div>

  <footer>
    <div>
      <div class='author'>
        <a href='index.php'><?php require('image/logo/logo.svg'); ?></a>
        <small>&copy; The Wind Ensemble 1985-<?php echo date('Y'); ?> All Rights Reserved.</small>
      </div>
      <div class='link'>
        <ul>
          <li><a href='https://member.winds-n.com'>団員専用ページ</a></li>
          <li><a href='policy.php'>個人情報保護方針</a></li>
        </ul>
      </div>
    </div>
  </footer>
  <script type="text/javascript" src="js/contact.js"></script>
  <script type="text/javascript" src="js/script.js"></script>
</body>
</html>
