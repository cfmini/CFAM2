<?php
/**
 *
 * @Author: 会飞的鱼
 * @Date: 2024/4/2 0002
 * @Time: 17:24
 * @Blog：https://houz.cn/
 * @Description: 会飞的鱼作品,禁止修改版权违者必究。
 */


namespace app\controller;


use app\middleware\AuthU;
use app\middleware\BaseMiddleware;
use app\middleware\LoginStatu;
use app\model\InviteLog;
use think\facade\Db;
use think\facade\View;

class User
{
    protected $middleware = [
        AuthU::class,
        LoginStatu::class,
        BaseMiddleware::class
    ];
    public function index(){
//        $GameLog = Db::connect('log_db')->table('CF_PLAY_LOG')
//            ->where('USN',session('USER_LOGIN_USN'))
//            ->limit(10)
//            ->order('START_DATE','desc')
//            ->select();
        $user = Db::connect('game_db')->table('CF_USER')
            ->where('USN', session('USER_LOGIN_USN'))
            ->find();
        $tac =  Db::connect('G4BOX_SA_BILL_DB')->table('TAccountCashMst')
            ->where('UserNo', session('USER_LOGIN_USN'))
            ->find();
        $gp = empty($user) ? '无角色' : $user['GAME_POINT'];
        $cf = empty($tac) ? '无角色' : $tac['Cash'];
        $nick = empty($user) ? '无角色' : $user['NICK'];
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
        });
        $nickss = iconv("GB18030", "UTF-8", iconv("UTF-8", "ISO-8859-1", $nick));
        restore_error_handler();
        $nick = empty($nickss) ? $nick:$nickss;

        View::assign([
            'gp'=>$gp,
            'cf'=>$cf,
            'nick'=>$nick,
        ]);
        return View();
    }

    public function profile(){
        //$user = $this->getUserByUsn(session('USER_LOGIN_USN'));
//        View::assign([
//            'user'=>$user,
//        ]);
        return View();
    }

    public function cdk(){
        return View();
    }

    public function Invite(){
        $inv = InviteLog::where('auserid',session('USER_LOGIN_ID'))->select();
        View::assign([
            'inv'=>$inv,
            'NumInv'=>count($inv),
            'cfNum' =>  InviteLog::where('auserid',session('USER_LOGIN_ID'))->sum('cf'),
        ]);
        return View();
    }

    protected function getUserByUsn($usn)
    {
        return Db::connect('game_db')->table('CF_MEMBER')->where('USN', $usn)->find();
    }

    public function ChangeName(){
        $user = Db::connect('game_db')->table('CF_USER')
            ->field('NICK')
            ->where('USN', session('USER_LOGIN_USN'))
            ->find();
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
        });

        if (!empty($user)){
            $nickss = iconv("GB18030", "UTF-8", iconv("UTF-8", "ISO-8859-1", $user['NICK']));
            restore_error_handler();
            $user['NICK'] = empty($nickss) ? $user['NICK']:$nickss;
        }


        View::assign([
            'nick'  =>  empty($user) ? '当前账号没有创建角色': $user['NICK'],
        ]);
        return View();
    }
}