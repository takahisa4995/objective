<?php
// session_destroy();
require 'function.php'; //関数
require 'message.php'; //定型文
require 'dqClass.php'; //クラスファイル
require 'instance.php'; //インスタンス生成
//POST送信(ゲーム制御)
// ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
if(!empty($_POST)){
    debug('POST送信開始');
    $attackFlg  = (!empty($_POST['attack']))  ? true : false;
    $startFlg   = (!empty($_POST['start']))   ? true : false;
    $escapeFlg  = (!empty($_POST['escape']))  ? true : false;
    $restartFlg = (!empty($_POST['restart'])) ? true : false;

    debug('ーーーーーーーーーーーーーーーーーーーー');
    debug('POST内容');
    debug(print_r($_POST,true));
    debug('セッション情報を表示');
    debug(print_r($_SESSION,true));
    debug('ーーーーーーーーーーーーーーーーーーーー');

    if($startFlg){
        //ゲームスタート
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
        if($_SESSION['human']->getHp() <= 0) $_SESSION['gamesetFlg'] = true;
        // gameOver();
  
      }else if($escapeFlg){
          //逃げた場合
          History::set(MSG14);
          createMonster();
      }else if($restartFlg){
          //リスタート
          gameOver();
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
    <h1 class="title">オブジェクト指向の練習</h1>
    <div class="wrap">
       <?php if(empty($_SESSION)){ ?>
        <h2 class="start">GAME START ?</h2>
        <form action="index.php" method="post">
            <input type="submit" name="start" value="▶︎はじめる">
        </form>
       <?php }else if(empty($_SESSION['gamesetFlg'])){ ?>

        <h2><?php echo $_SESSION['monster']->getName().MSG09; ?></h2>
        <div class="name-monster">
            <img src="<?php echo $_SESSION['monster']->getImg(); ?>">
        </div>
        <p class="hp-monster">モンスターのHP：<?php echo $_SESSION['monster']->getHp(); ?></p>
        <p>倒したモンスターの数：<?php echo $_SESSION['knockDownCount']; ?></p>
        <p><?php echo $_SESSION['human']->getName(); ?>の残りHP：<?php echo $_SESSION['human']->getHp(); ?></p>
        <form class="action" action="" method="post">
            <input type="submit" name="attack" value="▶︎攻撃する">
            <input type="submit" name="escape" value="▶︎逃げる">
            <input type="submit" name="restart" value="▶︎ゲームリスタート">
        </form>
       <?php }else if($_SESSION['gamesetFlg'] === true){ ?>
        <!-- リザルト画面を表示 -->
        <h1>Result</h1>
        <p>倒したモンスターの数：<?php echo $_SESSION['knockDownCount']; ?></p>
        <form class="action" action="" method="post">
            <input type="submit" name="restart" value="▶︎ゲームリスタート">
        </form>

       <?php } ?>
       <div class="history">
         <p><?php echo (!empty($_SESSION['history'])) ? $_SESSION['history'] : ''; ?></p>
       </div>
    </div>
</body>