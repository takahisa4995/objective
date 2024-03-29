<?php

// クラス
// ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー

// 性別クラス
class Sex{
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
        // $base_num = (($this->atk) * 2) - ($targetObj->getDef() * 4);
        $base_num = 10;
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
    protected $Sex;
    public function __construct($name,$Sex,$hp,$mp,$atk,$def,$spd){
        $this->name = $name;
        $this->Sex =$Sex;
        $this->hp = $hp;
        $this->mp = $mp;
        $this->atk = $atk;
        $this->def = $def;
        $this->spd = $spd;
    }
    public function setSex($num){
        $this->Sex = $num;
    }
    public function sayCry(){
        History::set($this->name . MSG03);
        switch($this->Sex){
            case Sex::MAN :
            History::set(MSG04);
            break;

            case Sex::WOMAN  :
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

?>