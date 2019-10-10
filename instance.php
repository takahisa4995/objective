<?php
// インスタンス生成
// ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー

// プレイヤー側インスタンス格納用
$humans = array();
$humans[] = new Human('村人',Gender::MAN,100,40,120,100,100);
$humans[] = new Human('勇者',Gender::MAN,1000,100,100,100,100);

// エネミー側インスタンス格納用
$monsters = array();
$monsters[] = new Monster('フランケン','img/mns/monster01.png',100,100,100,100,100);
$monsters[] = new Monster('フランケンNEO','img/mns/monster02.png',100,100,100,100,100);
$monsters[] = new Monster('ドラキュリー','img/mns/monster03.png',100,100,100,100,100);
// debug("monsters変数：");
// debug(print_r($monsters,true));
// $monsters[] = new MagicMonster('フランケンNEO',200,'img/monster02.png',20,40,mt_rand(50,100));
// $monsters[] = new Monster('ドラキュリー',100,'img/monster03.png',20,40);
// $monsters[] = new Monster('ドラキュラ伯爵',100,'img/monster04.png',20,40);
// $monsters[] = new Monster('スカルフェイス',100,'img/monster05.png',20,40);
// $monsters[] = new Monster('毒ハンド',100,'img/monster06.png',20,40);
// $monsters[] = new Monster('沼ハンド',100,'img/monster07.png',20,40);
// $monsters[] = new Monster('血のハンド',100,'img/monster08.png',20,40);

?>