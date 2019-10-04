<?php

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

?>