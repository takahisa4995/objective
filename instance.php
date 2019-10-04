<?php
// インスタンス生成
// ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー

// プレイヤー側インスタンス格納用
$humans = array();
$humans[] = new Human('村人',Gender::MAN,100,40,120);
$humans[] = new Human('勇者',Gender::MAN,1000,100,300);

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