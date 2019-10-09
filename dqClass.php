<?php

// クラス
// ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー

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

    //対象(引数のクリーチャー)に通常攻撃
    public function attack($targetObj){
        // ダメージ計算式(通常攻撃)
        // ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
        // 基本ダメージ ＝ (攻撃力 / 2) - (防御力 / 4)
        // ダメージ幅   ＝ (基本ダメージ / 16) + 1
        // 実ダメージ   = 基本ダメージ ± ダメージ幅 
        // ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
        $base_num = (($this->atk) * 2) - ($targetObj->getDef() * 4);
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
        $targetObj->setHp($targetObj->getHp() - $damege);
        History::set($damege . MSG02);
    }
}
//人(プレイヤー側)クラス
class Human extends Creature{
    protected $gender;
    public function __construct($name,$gender,$hp,$mp,$atk,$def,$spd){
        $this->name = $name;
        $this->gender =$gender;
        $this->hp = $hp;
        $this->mp = $mp;
        $this->atk = $atk;
        $this->def = $def;
        $this->spd = $spd;
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
    public function __construct($name,$img,$hp,$mp,$atk,$def,$spd){
        $this->name = $name;
        $this->img = $img;
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

// 魔法を使えるモンスタークラス
class MagicMonster extends Monster{
    private $magicAttack;
    public function __construct($name,$img,$hp,$mp,$atk,$def,$spd,$magicAttack){
        parent::__construct($name,$hp,$img,$$hp,$mp,$atk,$def,$spd);
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