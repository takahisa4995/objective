<?php

require 'dqClass.php'; //クラスファイル
require 'instance.php'; //インスタンス生成
require 'message.php'; //定型文
require 'function.php'; //関数


//POST送信
// ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
if(!empty($_POST)){
    debug('POST送信開始');
    $attackFlg = (!empty($_POST['attack'])) ? true : false;
    $startFlg = (!empty($_POST['start'])) ? true : false;
    $escapeFlg = (!empty($_POST['escape'])) ? true : false;
    debug('ーーーーーーーーーーーーーーーーーーーー');
    debug('atatckFlg:'.$attackFlg);
    debug('startFlg:'.$startFlg);
    debug('escapeFlg:'.$escapeFlg);
    debug('POST送信');
    debug(print_r($_POST,true));
    debug('セッション情報を表示');
    debug(print_r($_SESSION,true));
    debug('ーーーーーーーーーーーーーーーーーーーー');

    if($startFlg){
        //ゲームスタート又はリセット
        History::set(MSG11);
        init();
    }else if($attackFlg){
        //攻撃するを選択した場合

        //プレイヤー側の攻撃
        History::set($_SESSION['human']->getName().MSG12);
        $_SESSION['human']->attack($_SESSION['monster']);
        $_SESSION['monster']->sayCry();

        //モンスターの攻撃
        History::set($_SESSION['monster']->getName().MSG12);
        $_SESSION['monster']->attack($_SESSION['human']);
        $_SESSION['human']->sayCry();
        
        //モンスター側のHPが0以下なら、次のモンスターをリスポーン
        if($_SESSION['monster']->getHp() <= 0) {
            History::set($_SESSION['monster']->getName().MSG13);
            createMonster();
            ++$_SESSION['knockDownCount'];
        }
        //プレイヤー側のHPが0以下でゲームオーバー
        if($_SESSION['human']->getHp() <= 0) gameOver();
  
      }else if($escapeFlg){
          //逃げた場合
          History::set(MSG14);
          createMonster();
      }
  
    $_POST = array();
  }
  
?>


<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Objective</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1 style="text-align:center; color:#333; ">オブジェクト指向の練習</h1>
    <div style="background:black; padding:15px; position: relative;">
       <?php if(empty($_SESSION)){ ?>
        <h2 style="margin-top:60px">GAME START ?</h2>
        <form action="" method="post">
            <input type="submit" name="start" value="▶︎はじめる">
        </form>
       <?php }else{ ?>
        <h2><?php echo $_SESSION['monster']->getName().MSG09; ?></h2>
        <div style="height: 170px; ">
            <img src="<?php echo $_SESSION['monster']->getImg(); ?>" style="width:120px; height:auto; margin:40px auto 0 auto; display:block;">
        </div>
        <p style="font-size:14px; text-align:left;">モンスターのHP：<?php echo $_SESSION['monster']->getHp(); ?></p>
        <p>倒したモンスターの数：<?php echo $_SESSION['knockDownCount']; ?></p>
        <p><?php echo $_SESSION['human']->getName(); ?>の残りHP：<?php echo $_SESSION['human']->getHp(); ?></p>
        <form action="" method="post">
            <input type="submit" name="attack" value="▶︎攻撃する">
            <input type="submit" name="escape" value="▶︎逃げる">
            <input type="submit" name="start" value="▶︎ゲームリスタート">
        </form>
       <?php } ?>
       <div style="position:absolute; right:-350px; top:0; color:black; width: 300px;">
         <p><?php echo (!empty($_SESSION['history'])) ? $_SESSION['history'] : ''; ?></p>
       </div>
    </div>
</body>