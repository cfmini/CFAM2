<?php
namespace app\controller;

use app\BaseController;
use app\middleware\AuthU;
use app\middleware\BaseMiddleware;
use app\model\Admins;
use app\model\Cdks;
use app\model\Configs;
use app\model\Events;
use app\model\InviteLog;
use app\model\News;
use app\model\Report;
use app\model\SenditemAuth;
use app\model\Shop;
use app\model\UserLog;
use think\captcha\facade\Captcha;
use think\facade\Cache;
use think\facade\Db;
use think\facade\View;
use think\Model;

class Index extends BaseController
{

    protected $middleware = [
        AuthU::class,
        BaseMiddleware::class
    ];


    public function index()
    {
        // 缓存键名
        $cacheKeyLogsA = 'user_logs_slice_0_10';
        $cacheKeyLogsB = 'user_logs_slice_10_20';
        $cacheKeyLogsC = 'user_logs_slice_20_30';
        $cacheKeyUserLev = 'user_lev_top_10';
        $cacheKeyUserGP = 'user_gp_top_10';
        $cacheKeyKill = 'user_kill_top_10';
        $cacheKeyCf = 'user_cf_top_10';
        $cacheKeyNumMember = 'num_member';

        // 尝试从缓存中获取数据
        $firstFive = Cache::get($cacheKeyLogsA);
        $secondFive = Cache::get($cacheKeyLogsB);
        $thirdFive = Cache::get($cacheKeyLogsC);
        $UserLev = Cache::get($cacheKeyUserLev);
        $UserGP = Cache::get($cacheKeyUserGP);
        $UserKill = Cache::get($cacheKeyKill);
        $UserCf = Cache::get($cacheKeyCf);
        $numMember = Cache::get($cacheKeyNumMember);
        if (!$firstFive) {
            $userLogs = UserLog::order('id', 'desc')->select();
            $firstFive = $userLogs->slice(0, 10);
            Cache::set($cacheKeyLogsA, $firstFive, 86400); // 缓存1小时
        }

        if (!$secondFive) {
            $secondFive = $userLogs->slice(10, 10);
            Cache::set($cacheKeyLogsB, $secondFive, 86400);
        }

        if (!$thirdFive) {
            // 同上
            $thirdFive = $userLogs->slice(20, 10);
            Cache::set($cacheKeyLogsC, $thirdFive, 86400);
        }

        if (!$UserLev) {
            $db = Db::connect('game_db');
            $UserLev = $db->table('CF_USER')->order('LEV', 'desc')->limit(10)->select();
            $UserLev = json_decode(json_encode($UserLev), true);
            $UserLev = array_map(function($user){
                set_error_handler(function($errno, $errstr, $errfile, $errline) {
                });
                $nickss = iconv("GB18030", "UTF-8", iconv("UTF-8", "ISO-8859-1", $user['NICK']));
                restore_error_handler();
                $user['NICK'] = empty($nickss) ? $user['NICK']:$nickss;
                return $user;
            }, $UserLev);
            Cache::set($cacheKeyUserLev, $UserLev, 86400);
        }

        if (!$UserGP) {
            $UserGP = Db::connect('game_db')->table('CF_USER')->order('GAME_POINT', 'desc')->limit(10)->select();
            $UserGP = json_decode(json_encode($UserGP), true);
            $UserGP = array_map(function($user){
                set_error_handler(function($errno, $errstr, $errfile, $errline) {
                });
                $nickss = iconv("GB18030", "UTF-8", iconv("UTF-8", "ISO-8859-1", $user['NICK']));
                restore_error_handler();
                $user['NICK'] = empty($nickss) ? $user['NICK']:$nickss;
                return $user;
            }, $UserGP);
            Cache::set($cacheKeyUserGP, $UserGP, 86400);
        }

//        if (!$UserKill) {
//            $UserKill = Db::connect('game_db')->table('CF_USER_PROFILE')->order('ENEMY_KILL_CNT', 'desc')->limit(10)->select();
//            $UserKill = json_decode(json_encode($UserKill), true);
//            $UserKill = array_map(function($user){
//                set_error_handler(function($errno, $errstr, $errfile, $errline) {
//                });
//                $nickss = iconv("GB18030", "UTF-8", iconv("UTF-8", "ISO-8859-1", $user['NICK']));
//                restore_error_handler();
//                $user['NICK'] = empty($nickss) ? $user['NICK']:$nickss;
//                return $user;
//            }, $UserKill);
//            Cache::set($cacheKeyKill, $UserKill, 86400);
//        }



//        if (!$UserCf) {
//            $UserCf = Db::connect('game_db')->table('CF_USER')->field('USN,NICK,REG_DATE')->select()->toArray();
//            $result = [];
//            foreach ($UserCf as $user) {
//                $username = Db::connect('G4BOX_SA_BILL_DB')->table('TAccountMst')->field('CashReal')->where('UserNo', $user['USN'])->find();
//                if ($username) {
//                    $user['CashReal'] = $username['CashReal'];
//                    $result[] = $user;
//                }
//            }
//            usort($result, function($a, $b) {
//                return $b['CashReal'] - $a['CashReal'];
//            });
//
//            $result = array_slice($result, 0, 10);
//
//            Cache::set($UserCf, $result, 86400);
//        }

        if (!$numMember) {

            $numMember = Db::connect('game_db')->table('CF_MEMBER')->count();

            Cache::set($cacheKeyNumMember, $numMember, 86400);
        }



        $userTop = UserLog::where('Invite', '>', 0)
            ->order('Invite', 'desc')
            ->limit(10)
            ->select();

        View::assign([
            'logsA' => $firstFive,
            'logsB' => $secondFive,
            'logsC' => $thirdFive,
            'UserLev' => $UserLev,
            'UserGP' => $UserGP,
            //'UserCf' => $result,
            'UserKill' => $UserKill,
            'numMember' => $numMember,
            'userTop'   =>   $userTop,
        ]);

        return View();
    }

    public function ItemSendUser(){
        $user = SenditemAuth::where('userid',session('USER_LOGIN_ID'))->find();
        if (!$user || $user['status'] != 1){
            return redirect("/");
        }
        return View();
    }

    public function shop(){
        $limit = 9;

        $keyword = input('keyword');

        $query = Shop::order('create_time', 'DESC')->where('status',1);

        if ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%');
            $count = $query->count();
        } else {
            $count = $query->count();
        }

        $list = $query->paginate($limit)->appends(['keyword' => $keyword]);

        //$list = Shop::where('status',1)->select();
        View::assign(['list' => $list, 'count' => $count]);
        return View();
    }

    public function news()
    {
        $limit = 10;
        $query = News::order('create_time', 'DESC');
        $list = $query->paginate($limit);
        foreach ($list as $item) {
            $item['title'] = mb_substr($item['title'], 0, 50, 'UTF-8');
            $item['content'] = mb_substr(strip_tags($item['content']), 0, 200, 'UTF-8');
        }
        $count = $query->count();
        View::assign(['list' => $list, 'count' => $count]);
        return View::fetch();
    }


    public function article($id = null){

        if (empty(intval($id))){
            return redirect("/");
        }
        $res = News::where('id',intval($id))->find();
        if (!$res){
            return redirect("/");
        }

        View::assign('res',$res);
        return View();
    }

    public function Activity($id = null){

        if (empty(intval($id))){
            return redirect("/");
        }
        $res = Events::where('id',intval($id))->find();
        if (!$res){
            return redirect("/");
        }
        $status = '<span class="badge text-bg-success">进行中</span>';
        if (time() < $res['start_time']){
            $status = '<span class="badge text-bg-warning">未开始</span>';
        } elseif(time() > $res['end_time']) {
            $status = '<span class="badge text-bg-danger">已结束</span>';
        }

        View::assign([
            'res'=>$res,
            'status'=>$status
        ]);
        return View();
    }

    public function signin(){
        if(!session('USER_LOGIN_ID')){
            return View();
        }else{
            return redirect("/");
        }
    }

    public function signup(){
        if(!session('USER_LOGIN_ID')){
            return View();
        }else{
            return redirect("/");
        }
    }

    public function events(){
        $limit = 9;

        $keyword = input('keyword');

        $query = Events::order('create_time', 'DESC');

        if ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%');
            $count = $query->count();
        } else {
            $count = $query->count();
        }

        $list = $query->paginate($limit)->appends(['keyword' => $keyword]);
        foreach ($list as &$event) {
            $event['title'] = mb_substr($event['title'], 0, 30, 'UTF-8');
            if (time() < $event['start_time']) {
                $event['status'] = '<span class="badge text-bg-warning">即将开始</span>';
            } elseif (time() > $event['end_time']) {
                $event['status'] = '<span class="badge text-bg-danger">已结束</span>';
            } else {
                $event['status'] = '<span class="badge text-bg-success">进行中</span>';
            }
        }

        View::assign(['list' => $list, 'count' => $count]);
        return View();
    }

    public function AdminCaptcha(){
        ob_clean();
        return Captcha::create('AdminCaptcha', true);
    }

    public function LoginCaptcha(){
        ob_clean();
        return Captcha::create('LoginCaptcha', true);
    }

    public function RegCaptcha(){
        ob_clean();
        return Captcha::create('LoginCaptcha', true);
    }

    public function AgenCaptcha(){
        ob_clean();
        return Captcha::create('AgenLoginCaptcha', true);
    }

    public function AdminLogin(){
        return View();
    }

    public function Agenlogin(){
        return View();
    }

    public function report(){
        $list = Report::where('reportType',1)->where('status',1)->select();
        foreach ($list as &$item) {
            $item['content'] = mb_substr(strip_tags($item['content']), 0, 10, 'UTF-8');
            if (mb_strlen(strip_tags($item['content']), 'UTF-8') > 10) {
                $item['content'] .= '...';
            }
        }
        View::assign('list',$list);
        return View();
    }

    public function AjaxLoginS(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        $Config = Configs::gets();
        if ($Config['loginapi'] != 1){
            return json([
                'code'  =>  502,
                'msg'   =>  '接口未开启'
            ]);
        }
        $user= input('user');
        $pass = input('pass');
        if (empty($user)  || empty($pass)){
            return json([
                'code'  =>  201,
                'msg'   =>  'user、pass都不能为空'
            ]);
        }
        $db = Db::connect('game_db');
        $userinfo = $db->table('CF_MEMBER')
            ->where('USER_ID', $user)
            ->whereRaw("USER_PASS = HASHBYTES('SHA1', '{$pass}')")
            ->find();

        if ($userinfo){
            $user = $db->table('CF_USER')
                ->where('USN', $userinfo['USN'])
                ->find();
            if (empty($user)){
                return json([
                    'code'  =>  201,
                    'msg'   =>  '账号密码正确',
                    'data'  =>  [
                        'USN'   =>  $userinfo['USN'],
                        'status'    =>  0,
                        'HOLD'  =>  ''
                    ]
                ]);
            }else{
                return json([
                    'code'  =>  200,
                    'msg'   =>  '账号密码正确',
                    'data'  =>  [
                        'USN'   =>  $userinfo['USN'],
                        'status'    =>  1,
                        'HOLD'  =>  $user['HOLD_TYPE']
                    ]
                ]);
            }

        }else{
            return json([
                'code'  =>  500,
                'msg'   =>  '账号密码错误',
            ]);
        }


    }


    public function AjaxSendCdk(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        $Config = Configs::gets();
        if ($Config['cdkapi'] != 1){
            return json([
                'code'  =>  502,
                'msg'   =>  '接口未开启'
            ]);
        }
        $user= input('user');
        //$pass = input('pass');
        $cdk = input('cdk');
        if (empty($user)  || empty($cdk)){
            return json([
                'code'  =>  201,
                'msg'   =>  'user、cdk都不能为空'
            ]);
        }
        $db = Db::connect('game_db');
        $userinfo = $db->table('CF_MEMBER')
            ->where('USER_ID', $user)
            //->whereRaw("USER_PASS = HASHBYTES('SHA1', '{$pass}')")
            ->find();

        if ($userinfo){
            $res = Cdks::where('code', $cdk)->find();
            if (!$res || $res['status'] == 1){
                return json([
                    'code' => ($res ? 501 : 500),
                    'msg' => ($res ? 'Cdk已被使用' : 'Cdk不存在')
                ]);
            }

            $itemsStr = $res['item'];
            $items = explode(',', $itemsStr);
            try {
                Db::connect('game_db')->startTrans();
                foreach ($items as $itemId) {
                    $result = Db::connect('game_db')->execute("EXECUTE USP_GIVE_CASH_ITEM @p_usn = ?, @p_log_type = ?, @p_item_id = ? , @p_Result = ?", [
                        $userinfo['USN'],
                        '',
                        $itemId,
                        0
                    ]);

                    if ($result !=0) {
                        Db::connect('game_db')->rollback();
                        return json([
                            'code' => 502,
                            'msg' => '发送失败：' . $itemId
                        ]);
                    }
                }
                Db::connect('game_db')->commit();
                if ($res['type'] == 1){
                    Cdks::where('code', $cdk)->update([
                        'status'    =>  1,
                        'name'=>    $userinfo['USER_ID']
                    ]);

                }
                return json([
                    'code'  =>  200,
                    'msg'   =>  '发送成功'
                ]);

            } catch (\Exception $e) {
                Db::connect('game_db')->rollback();
                return json([
                    'code'  =>  503,
                    'msg'   =>  '发送过程中发生错误：' . $e->getMessage()
                ]);
            }
        }else{
            return json([
                'code'  =>  201,
                'msg'   =>  '用户不存在'
            ]);
        }
    }

    public function AjaxReg(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        $Config = Configs::gets();
        if ($Config['regapi'] != 1){
            return json([
                'code'  =>  502,
                'msg'   =>  '接口未开启'
            ]);
        }
        $uid = input('uid');
        $token = input('token');
        $user= input('user');
        $pass = input('pass');
        $userId = input('userId');

        if (empty($uid) || empty($token) || empty($user) || empty($pass)){
            return json([
                'code'  =>  201,
                'msg'   =>  'uid、token、user、pass都不能为空'
            ]);
        }
        $info = Admins::where('token',$token)
            ->where('id',$uid)
            ->find();
        if ($info){
            $db = Db::connect('game_db');
            $result = $db->table('CF_MEMBER')->where('USER_ID', $user)
                ->find();



            if ($Config['InSwitch']==1){
                //判断邀请注册是否开启如果开启先检测邀请账号是否存在
                if (!empty($userId)){
                    $users = UserLog::where('userid',$userId)->find();


                    if (empty($users)){
                        return json([
                            'code'  =>  507,
                            'msg'   =>  '邀请人账号不存在，可能账号不是在本站注册的'
                        ]);
                    }
                    //通过邀请注册后被邀请人邀请次数+1并发送CF点
                    UserLog::where('userid', $users['userid'])
                        ->inc('Invite', 1)
                        ->update();

                    $uid = Db::connect('game_db')->table('CF_MEMBER')->where('USER_ID',$users['userid'])->find();
                    //被邀请人获得CF点
                    $sql = "EXECUTE givecash @Cash = ?, @UserID = ?";
                    Db::connect('G4BOX_SA_BILL_DB')->execute($sql, [$Config['aValue'], $uid['USN']]);

                    $InviteLog = new InviteLog();
                    $InviteLog->auserid = $userId;
                    $InviteLog->buserid = $user;
                    $InviteLog->status = 1;
                    $InviteLog->cf = $Config['aValue'];
                    $InviteLog->create_time = time();
                    $InviteLog->save();
                }

            }
            if ($result){
                return json([
                    'code'  =>  202,
                    'msg'   =>  '账号已存在'
                ]);
            }

            $db->table('CF_MEMBER')->insert([
                'USER_ID' => $user,
                'LUSER_ID' => $user,
                'USER_PASS' => Db::raw("HASHBYTES('SHA1', '{$pass}')"),
                'DURATION' => 0,
            ]);

            $userLog = new UserLog();
            $userLog->userid = $user;
            $userLog->email = '';
            $userLog->create_time = time();
            $userLog->ip = request()->ip();
            $userLog->Invite = 0;
            $userLog->type = 2;
            $userLog->save();

            return json([
                'code'  =>  200,
                'msg'   =>  '注册成功'
            ]);

        }else{
            return json([
                'code'  =>  500,
                'msg'   =>  'Token错误'
            ]);
        }
    }

    public function JieApi(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        $Config = Configs::gets();
        if ($Config['jieapi'] != 1){
            return json([
                'code'  =>  502,
                'msg'   =>  '接口未开启'
            ]);
        }
        $uid = input('uid');
        $token = input('token');
        $user= input('user');
        $type= input('type');

        if (empty($type) ||empty($user) || empty($token) || empty($uid)){
            return json([
                'code'  =>  201,
                'msg'   =>  'user、type、token、usn都不能为空'
            ]);
        }
        $info = Admins::where('token',$token)
            ->where('id',$uid)
            ->find();
        if ($info){
            $db = Db::connect('game_db');
            $user = $db->table('CF_MEMBER')->where('USER_ID', $user)
                ->find();
            $result = $db->table('CF_USER')->where('USN', $user['USN'])
                ->find();

            if (!$result){
                return json([
                    'code'  =>  202,
                    'msg'   =>  '该账号下无角色'
                ]);
            }

            if ($type == 1){
                $db->table('CF_USER')->where('USN',$result['USN'])->update([
                    'HOLD_TYPE' => 'A',
                ]);


                return json([
                    'code'  =>  200,
                    'msg'   =>  '解封成功'
                ]);
            }elseif($type == 2){
                $db->table('CF_USER')->where('USN',$result['USN'])->update([
                    'HOLD_TYPE' => 'E',
                ]);


                return json([
                    'code'  =>  200,
                    'msg'   =>  '封号成功'
                ]);
            } else{
                return json([
                    'code'  =>  501,
                    'msg'   =>  '参数错误'
                ]);
            }



        }else{
            return json([
                'code'  =>  500,
                'msg'   =>  'Token错误'
            ]);
        }
    }

    public function SendItemApi(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        $Config = Configs::gets();
        if ($Config['itemapi'] != 1){
            return json([
                'code'  =>  502,
                'msg'   =>  '接口未开启'
            ]);
        }
        $uid = input('uid');
        $token = input('token');
        $user= input('user');
        $item = input('itemid');
        if ( !preg_match('/^\d+$/', $item)) {
            return json([
                'code'  =>  202,
                'msg'   =>  'itemid只能是数字'
            ]);
        }
        if (empty($uid) || empty($token) || empty($user) || empty($item)){
            return json([
                'code'  =>  201,
                'msg'   =>  'uid、token、user、itemid都不能为空'
            ]);
        }
        $ietm = Db::connect('game_db')->table('CF_ITEM_INFO')->where('ITEM_ID', $item)
            ->find();
        if (!$ietm){
            return json([
                'code'  =>  203,
                'msg'   =>  '物品ID不正确'
            ]);
        }
        $info = Admins::where('token',$token)
            ->where('id',$uid)
            ->find();
        if ($info){
            $db = Db::connect('game_db');
            $user = $db->table('CF_MEMBER')->where('USER_ID', $user)
                ->find();
            $result = $db->table('CF_USER')->where('USN', $user['USN'])
                ->find();

            if (!$result){
                return json([
                    'code'  =>  202,
                    'msg'   =>  '该账号下无角色'
                ]);
            }

            $sql = "EXECUTE USP_GIVE_CASH_ITEM @p_usn = ?,@p_log_type = ? ,@p_item_id = ?, @p_Result = ?";
            Db::connect('game_db')->execute($sql, [$result['USN'],'', $item,0]);


            return json([
                'code'  =>  200,
                'msg'   =>  '发送成功'
            ]);

        }else{
            return json([
                'code'  =>  500,
                'msg'   =>  'Token错误'
            ]);
        }
    }

}
