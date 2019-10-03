<?php

require 'function.php';
require 'message.php';





// エネミー格納用
$Monster = array();

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
    protected $attackMin;
    protected $attackMax;
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
    //対象(引数のクリーチャー)に攻撃
    public function attack($targetObj){
        $attackPoint = mt_rand($this->attackMin,$this->attackMax);
        if(!mt_rand(0,9)){
            //10%でクリティカル攻撃(1.5倍ダメージ)
            // $attackPoint = $attackPoint * 1.5;
            // $attackPoint = (int)$attackPoint;
            $attackPoint = (int)($attackPoint * 1.5);
            History::set($this->getName() . MSG01);
        }
        $targetObj->setHp($targetObj->getHp() - $attackPoint);
        History::set($attackPoint . MSG02);
    }
}
//人(プレイヤー側)クラス
class Human extends Creature{
    protected $gender;
    public function __construct($name,$gender,$hp,$attackMin,$attackMax){
        $this->name = $name;
        $this->gender =$gender;
        $this->hp = $hp;
        $this->attackMin = $attackMin;
        $this->attackMax = $attackMax;
    }
    public function setGender($num){
        $this->gender = $num;
    }
    public function sayCry(){
        History::set($this->name . MSG03);
        switch($this->gender){
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
    public function __construct($name,$hp,$img,$attackMin,$attackMax){
        $this->name = $name;
        $this->hp = $hp;
        $this->img = $img;
        $this->attackMin = $attackMin;
        $this->attackMax = $attackMax;
    }
    public function getImg(){
        return $this->img;
    }
    public function sayCry(){
        History::set($this->name . MSG03);
        History::set($this->name . MSG07);
    }
}

// 魔法を使えるモンスタークラス
class MagicMonster extends Monster{
    private $magicAttack;
    public function __construct($name,$hp,$img,$attackMin,$attackMax,$magicAttack){
        parent::__construct($name,$hp,$img,$attackMin,$attackMax);
        $this->magicAttack = $magicAttack;
    }
    public function getMagicAttack(){
        return $this->magicAttack;
    }
    public function attack($targetObj){
        // 25%の確率で魔法攻撃
        if(!mt_rand(0,3)){
            History::set($this->name.MSG08);
            $targetObj->setHp( $targetObj->getHp() - $this->magicAttack);
            History::set($this->magicAttack.MSG02);
        }else{
            parent::attack($targetObj);
        }
    }
}
// interface HistoryInterface{
//     public static function set();
//     public static function clear();
// }
// 履歴管理クラス
// class History implements HistoryInterface{
class History{
    public static function set($str){
        if(empty($_SESSION['history'])) $_SESSION['history'] = '';
        $_SESSION['history'] .= $str .'<br>';
    }
    public static function clear(){
        unset($_SESSION['history']);
    }
}

// インスタンス生成
$humans[] = new Human('村人',Gender::MAN,100,40,120);
$humans[] = new Human('勇者',Gender::WOMAN,1000,100,300);

$monsters[] = new Monster('フランケン',100,'img/monster01.png',20,40);
$monsters[] = new MagicMonster('フランケンNEO',200,'img/monster02.png',20,40,mt_rand(50,100));
$monsters[] = new Monster('ドラキュリー',100,'img/monster03.png',20,40);
$monsters[] = new Monster('ドラキュラ伯爵',100,'img/monster04.png',20,40);
$monsters[] = new Monster('スカルフェイス',100,'img/monster05.png',20,40);
$monsters[] = new Monster('毒ハンド',100,'img/monster06.png',20,40);
$monsters[] = new Monster('沼ハンド',100,'img/monster07.png',20,40);
$monsters[] = new Monster('血のハンド',100,'img/monster08.png',20,40);
// debug('モンスター一覧');
// debug(print_r($monsters,true));

function createMonster(){
    global $monsters;
    // $monster = $monsters[mt_rand(0,7)];
    // $_SESSION['monster'] = $monster;
    // History::set($monster->getName().MSG09);

    $_SESSION['monster'] = $monsters[mt_rand(0,7)];
    History::set($_SESSION['monster']->getName().MSG09);

}
function createHuman(){
    global $humans;
    $_SESSION['human'] = $humans[mt_rand(0,1)];
}

function init(){
    History::clear();
    History::set(MSG10);
    $_SESSION['knockDownCount'] = 0;
    createHuman();
    createMonster();
}
function gameOver(){
    $_POST = array();
    $_SESSION = array();
}

//POST送信
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
        // debug('セッション情報を表示');
        // debug(print_r($_SESSION,true));
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

    }else if($escape){
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
    <style>
    	body{
	    	margin: 50px auto;
	    	padding: 0 150px;
	    	width: 25%;
	    	background: #fbfbfa;
        color: white;
    	}
    	h1{ color: white; font-size: 20px; text-align: center;}
      h2{ color: white; font-size: 16px; text-align: center;}
    	form{
	    	overflow: hidden;
    	}
    	input[type="text"]{
    		color: #545454;
	    	height: 60px;
	    	width: 100%;
	    	padding: 5px 10px;
	    	font-size: 16px;
	    	display: block;
	    	margin-bottom: 10px;
	    	box-sizing: border-box;
    	}
      input[type="password"]{
    		color: #545454;
	    	height: 60px;
	    	width: 100%;
	    	padding: 5px 10px;
	    	font-size: 16px;
	    	display: block;
	    	margin-bottom: 10px;
	    	box-sizing: border-box;
    	}
    	input[type="submit"]{
	    	border: none;
	    	padding: 15px 30px;
	    	margin-bottom: 15px;
	    	background: black;
	    	color: white;
	    	float: right;
    	}
    	input[type="submit"]:hover{
	    	background: #3d3938;
	    	cursor: pointer;
    	}
    	a{
 color: #545454;
	    	display: block;
    	}
    	a:hover{
	    	text-decoration: none;
    	}
    </style>
</head>
<body>
    <h1 style="text-align:center; color:#333; ">ドラ◯エのパクリ!!」</h1>
    <div style="background:black; padding:15px; position: relative;">
       <?php if(empty($_SESSION)){ ?>
        <h2 style="margin-top:60px">GAME START ?</h2>
        <form action="" method="post">
            <input type="submit" name="start" value="▶︎はじめる">
        </form>
       <?php }else{ ?>
        <h2><?php echo $_SESSION['monster']->getName().MSG09; ?></h2>
        <div style="height: 150px; ">
            <img src="<?php echo $_SESSION['monster']->getImg(); ?>" style="width:120px; height:auto; margin:40px auto 0 auto; display:block;">
        </div>
        <p style="font-size:14px; text-align:center;">モンスターのHP：<?php echo $_SESSION['monster']->getHp(); ?></p>
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
</!DOCTYPE>