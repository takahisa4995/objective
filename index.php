<?php

require 'function.php'; //関数
require 'message.php'; //定型文
require 'dqClass.php'; //クラスファイル


// プレイヤー側インスタンス格納用
$humans = array();
$humans[] = new Human('村人',Gender::MAN,100,40,120);
$humans[] = new Human('勇者',Gender::WOMAN,1000,100,300);

// エネミー側インスタンス格納用
$Monster = array();
$monsters[] = new Monster('フランケン',100,'img/monster01.png',20,40);
$monsters[] = new MagicMonster('フランケンNEO',200,'img/monster02.png',20,40,mt_rand(50,100));
$monsters[] = new Monster('ドラキュリー',100,'img/monster03.png',20,40);
$monsters[] = new Monster('ドラキュラ伯爵',100,'img/monster04.png',20,40);
$monsters[] = new Monster('スカルフェイス',100,'img/monster05.png',20,40);
$monsters[] = new Monster('毒ハンド',100,'img/monster06.png',20,40);
$monsters[] = new Monster('沼ハンド',100,'img/monster07.png',20,40);
$monsters[] = new Monster('血のハンド',100,'img/monster08.png',20,40);

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
    <h1 style="text-align:center; color:#333; ">ドラ◯エの的な」</h1>
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