<?php
// インスタンス生成
// ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー

// プレイヤー側インスタンス格納用
$humans = array();
$humans[] = new Human('村人',Gender::MAN,100,100,100,100,100);
$humans[] = new Human('勇者',Gender::WOMAN,1000,1000,300,300,300);

// エネミー側インスタンス格納用
$Monster = array();
$monsters[] = new Monster('フランケン','img/monster/monster01.png',200,200,50,50,50);
$monsters[] = new Monster('フランケンNEO','img/monster/monster02.png',200,200,50,50,50);
// $monsters[] = new MagicMonster('フランケンNEO','img/monster/monster02.png',200,400,100,100,100);
// $monsters[] = new Monster('ドラキュリー','img/monster/monster03.png',20,40);
// $monsters[] = new Monster('ドラキュラ伯爵','img/monster/monster04.png',20,40);
// $monsters[] = new Monster('スカルフェイス','img/monster/monster05.png',20,40);
// $monsters[] = new Monster('毒ハンド','img/monster/monster06.png',20,40);
// $monsters[] = new Monster('沼ハンド','img/monster/monster07.png',20,40);
// $monsters[] = new Monster('血のハンド','img/monster/monster08.png',20,40);

?>