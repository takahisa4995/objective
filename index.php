<?php

require_once 'init.php'; //セッション、デバッグ設定
// require 'dqClass.php'; //クラスファイル

// 性別クラス
class Gender{
    const MAN = 1;
    const WOMAN = 2;
    const OTHER = 3;
}
// 生き物クラス(抽象クラス)
abstract class Creature{
    protected $name;
    protected $hp;
    protected $mp;
    protected $atk;
    protected $def;
    protected $spd;
 
    abstract public function sayCry();
    
    public function setName($str){
        $this->name = $str;
    }
    public function getName(){
        return $this->name;
    }
    public function setHp($num){
        $this->hp = $num;
    }
    public function getHp(){
        return $this->hp;
    }
    public function setMp($num){
        $this->mp = $num;
    }
    public function getMp(){
        return $this->mp;
    }
    public function getDef(){
        return $this->def;
    }
    public function getSpd(){
        return $this->spd;
    }


    //対象(引数のクリーチャー)に攻撃
    public function attack($targetObj){
        // ダメージ計算式(通常攻撃)
        // ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
        // 基本ダメージ ＝ (攻撃力 / 2) - (防御力 / 4)
        // ダメージ幅   ＝ (基本ダメージ / 16) + 1
        // 実ダメージ   = 基本ダメージ ± ダメージ幅 
        // ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
        $base_num = (($this->atk) * 2) - ($targetObj->getDef() * 4);
        // 基本ダメージがマイナスの場合は0に修正
        $base_num = ($base_num < 0) ? 0 : $damege;
        $rand_num = ($base_num / 16) + 1;
        $damege = (int)($base_num + mt_rand(((-1) * $rand_num),$rand_num));
        debug('$base_num =' . $base_num);
        debug('$rand_num =' . $rand_num);
        debug('$damege =' . $damege);
        if(!mt_rand(0,9)){
            //10%で会心の一撃
            // 実ダメージ = 攻撃力 * (95% ~ 105%)
            $damege = (int)( $this->atk * (rand(0.95,1.05)));
            History::set($this->getName() . MSG01);
        }
        
        $damege = ($damege < 0) ? 0 : $damege;
        debug("ダメージ量：".$damege);
        $targetObj->setHp($targetObj->getHp() - $damege);
        History::set($damege . MSG02);
    }
}
//人(プレイヤー側)クラス
class Human extends Creature{
    protected $Gender;
    public function __construct($name,$Gender,$hp,$mp,$atk,$def,$spd){
        $this->name = $name;
        $this->Gender =$Gender;
        $this->hp = $hp;
        $this->mp = $mp;
        $this->atk = $atk;
        $this->def = $def;
        $this->spd = $spd;
    }
    public function setGender($num){
        $this->Gender = $num;
    }
    public function sayCry(){
        History::set($this->name . MSG03);
        switch($this->Gender){
            case Gender::MAN :
            History::set(MSG04);
            break;

            case Gender::WOMAN  :
            History::set(MSG05);
            break;

            default : 
            History::set(MSG06);
        }
    }
}
//エネミークラス
class Monster extends Creature{
    //見た目表示用
    protected $img;
    public function __construct($name,$img,$hp,$mp,$atk,$def,$spd){
        $this->name = $name;
        $this->img =$img;
        $this->hp = $hp;
        $this->mp = $mp;
        $this->atk = $atk;
        $this->def = $def;
        $this->spd = $spd;
    }
    public function getImg(){
        return $this->img;
    }
    public function sayCry(){
        History::set($this->name . MSG03);
        History::set($this->name . MSG07);
    }
}

// 履歴管理クラス
class History{
    public static function set($str){
        if(empty($_SESSION['history'])) $_SESSION['history'] = '';
        $_SESSION['history'] .= $str .'<br>';
    }
    public static function clear(){
        unset($_SESSION['history']);
    }
}

require 'instance.php'; //インスタンス生成
require 'message.php'; //定型文
require 'function.php'; //関数

//POST送信(ゲーム制御)
// ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
if(!empty($_POST)){
    debug('POST送信開始');
    $attackFlg  = (!empty($_POST['attack']))  ? true : false;
    $startFlg   = (!empty($_POST['start']))   ? true : false;
    $escapeFlg  = (!empty($_POST['escape']))  ? true : false;
    $restartFlg = (!empty($_POST['restart'])) ? true : false;
    // debug('ーーーーーーーーーーーーーーーーーーーー');
    // debug('POST送信');
    // debug(print_r($_POST,true));
    // debug('セッション情報を表示');
    // debug(print_r($_SESSION,true));
    // debug('ーーーーーーーーーーーーーーーーーーーー');

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
        <form action="" method="post">
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
        <form action="" method="post">
            <input type="submit" name="attack" value="▶︎攻撃する">
            <input type="submit" name="escape" value="▶︎逃げる">
            <input type="submit" name="restart" value="▶︎ゲームリスタート">
        </form>
       <?php }else if($_SESSION['gamesetFlg'] === true){ ?>
        <!-- リザルト画面を表示 -->
        <h1>Result</h1>
        <p>倒したモンスターの数：<?php echo $_SESSION['knockDownCount']; ?></p>
        <form action="" method="post">
            <input type="submit" name="restart" value="▶︎ゲームリスタート">
        </form>

       <?php }else { ?>
        <form action="" method="post">
            <input type="submit" name="restart" value="▶︎ゲームリスタート">
        </form>
       <?php } ?>

       <div class="history">
         <p><?php echo (!empty($_SESSION['history'])) ? $_SESSION['history'] : ''; ?></p>
       </div>
    </div>
</body>