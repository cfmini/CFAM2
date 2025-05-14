<?php
/**
 *
 * @Author: 会飞的鱼
 * @Date: 2024/4/1 0001
 * @Time: 15:26
 * @Blog：https://houz.cn/
 * @Description: 会飞的鱼作品,禁止修改版权违者必究。
 */


namespace app\controller;
use app\model\AdminLog;
use app\model\Admins;
use app\model\Configs;
use app\model\Events;
use app\model\EventsLog;
use app\model\Invitation;
use app\model\InviteLog;
use app\model\Report;
use app\model\SenditemAuth;
use app\model\Shop;
use app\model\ShopLog;
use app\model\UserLog;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\Model;

class Post
{
    public function Signin(){
        //注册
        $siteConfig = Configs::gets();
        $Invitation = input('post.Invitation');
        $username = input('post.username');
        $email = input('post.email');
        $password = input('post.password');
        $password2 = input('post.password2');
        $ip = request()->ip();

        if ($siteConfig['in'] == 1){
            if (empty($Invitation)){
                return json([
                    'code'  =>  207,
                    'msg'   =>  '邀请码不能为空'
                ]);
            }
            $In = Invitation::where('code',$Invitation)->find();

            if (!$In){
                return json([
                    'code'  =>  208,
                    'msg'   =>  '邀请码不存在'
                ]);
            }

            if ($In['status'] == 1){
                return json([
                    'code'  =>  209,
                    'msg'   =>  '邀请码已被使用'
                ]);
            }


        }


        if ($siteConfig['reg_switch'] == 0){
            return json([
                'code'  =>  501,
                'msg'   =>  '暂时未开放注册'
            ]);
        }
        if (empty($username) || empty($password) || empty($password2)) {
            return json([
                'code'  =>  202,
                'msg'   =>  '每一项都不能为空'
            ]);
        }
        if ($password !== $password2) {
            return json([
                'code'  =>  205,
                'msg'   =>  '两次输入的密码不一致'
            ]);
        }
        $validate = new \app\validate\UserV();
        if (!$validate->check(['username'=>$username,'password'=>$password,'password2'=>$password2])) {
            return json([
                'code' => 203,
                'msg' => $validate->getError()
            ]);
        }
        $code = request()->param('RegCode');
        if (!captcha_check($code)) {
            return json([
                'code'  =>  203,
                'msg'   =>  '验证码错误!'
            ]);
        }

        $db = Db::connect('game_db');
        $result = $db->table('CF_MEMBER')->where('USER_ID', $username)
            ->find();

        if ($result){
            return json([
                'code'  =>  202,
                'msg'   =>  '账号已存在'
            ]);
        }
        $userLog = UserLog::where('email',$email)->find();
        if ($userLog){
            return json([
                'code'  =>  203,
                'msg'   =>  '邮箱已存在'
            ]);
        }

        if ($siteConfig['InSwitch']==1){

            //判断邀请注册是否开启如果开启先检测邀请账号是否存在
            $userId = input('userId');

            if (!empty($userId)){
                $user = UserLog::where('userid',$userId)->find();
                if (empty($user)){
                    return json([
                        'code'  =>  507,
                        'msg'   =>  '邀请人账号不存在，可能账号不是在本站注册的'
                    ]);
                }
                //通过邀请注册后被邀请人邀请次数+1并发送CF点
                UserLog::where('userid', $user['userid'])
                    ->inc('Invite', 1)
                    ->update();

                $uid = $db->table('CF_MEMBER')->where('USER_ID',$user['userid'])->find();
                //被邀请人获得CF点
                $sql = "EXECUTE givecash @Cash = ?, @UserID = ?";
                Db::connect('G4BOX_SA_BILL_DB')->execute($sql, [$siteConfig['aValue'], $uid['USN']]);

                $InviteLog = new InviteLog();
                $InviteLog->auserid = $userId;
                $InviteLog->buserid = $username;
                $InviteLog->status = 1;
                $InviteLog->cf = $siteConfig['aValue'];
                $InviteLog->create_time = time();
                $InviteLog->save();
            }

        }

        //            $db->table('CF_MEMBER')->insert([
//                'USER_ID' => $username,
//                'LUSER_ID' => $username,
//                'USER_PASS' => Db::raw("HASHBYTES('SHA1', '{$password}')"),
//                'DURATION' => 0,
//            ]);

        $sql = "EXECUTE PROC_WEB_USER_INFO_INS @p_User = ?, @p_User_pass = ? , @p_Mail = ? ,@p_Result=0";
        Db::connect('game_db')->execute($sql, [$username, $password,$email]);

        //获取自增ID
        //$lastInsertId =$db->table('CF_MEMBER')->getLastInsID();

        $userLog = new UserLog();
        $userLog->userid = $username;
        $userLog->email = $email;
        $userLog->create_time = time();
        $userLog->ip = $ip;
        $userLog->Invite = 0;
        $userLog->type = 1;
        $userLog->save();


        //注册成功自动赠送CF点
//        $sql = "EXECUTE givecash @Cash = ?, @UserID = ?";
//        $num = 100;
//        Db::connect('G4BOX_SA_BILL_DB')->execute($sql, [$num, $lastInsertId]);

        Invitation::where('code',$Invitation)->update([
            'status'    =>  1,
            'username'  =>  $username
        ]);



        return json([
            'code'  =>  200,
            'msg'   =>  '注册成功'
        ]);
    }

    public function login(){
        if (empty(input('username')) || empty(input('password'))){
            return json([
                'code'  =>  202,
                'msg'   =>  '每一项都不能为空'
            ]);
        }

        $code = request()->param('LoginCode');
        if (!captcha_check($code)) {
            return json([
                'code'  =>  203,
                'msg'   =>  '验证码错误!'
            ]);
        }

        $username = input('username');
        $password = input('password');
        $specialChars = '!@#$%^&*()_-+=[]{}|;:\'",.<>/?\\';

        if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            return json([
                'code'  =>  207,
                'msg'   =>  '用户名只能是数字和字母'
            ]);
        }
        if (!preg_match('/^[\x{002d}\x{0030}-\x{0039}\x{0041}-\x{005A}\x{0061}-\x{007A}' . preg_quote($specialChars, '/') . ']*$/u', $password)) {
            return json([
                'code'  =>  207,
                'msg'   =>  '密码只能是数字、字母、特殊符号'
            ]);
        }

        $result = Db::connect('game_db')->table('CF_MEMBER')->where('USER_ID', $username)
            ->find();

        if (!$result){
            return json([
                'code' => 501,
                'msg' => '账号不存在！'
            ]);
        }

        if (md5($password.'sB.0zJu!3Po8>9g5') != $result['USER_PASS']){
            return json([
                'code' => 502,
                'msg' => '密码不正确！'
            ]);
        }


        session('USER_LOGIN_ID', $result['USER_ID']);
        session('USER_LOGIN_USN', $result['USN']);
        return json([
            'code'  =>  200,
            'msg'   =>  '登录成功'
        ]);


//        if ($result){
//            session('USER_LOGIN_ID', $result['USER_ID']);
//            session('USER_LOGIN_USN', $result['USN']);
//            return json([
//                'code'  =>  200,
//                'msg'   =>  '登录成功'
//            ]);
//        }else{
//            return json([
//                'code'  =>  201,
//                'msg'   =>  '密码错误或用户不存在'
//            ]);
//        }

    }

    public function logout(){
        session::delete('USER_LOGIN_ID');
        session::delete('USER_LOGIN_USN');
        return redirect("/signin");
    }

    public function quit(){
        session::delete('Agency_LOGIN_ID');
        session::delete('Agency_LOGIN_name');
        return redirect("/");
    }

    public function PostAdminLogin(){
        $code = request()->param('AdminCaptcha');
        if (!captcha_check($code)) {
            return json([
                'code'  =>  203,
                'msg'   =>  '验证码错误!'
            ]);
        }
        if (request()->isPost()) {
            $username = request()->param('username');
            $password = request()->param('password');

            // 查询数据库验证用户名和密码
            $admin = Admins::where('username', $username)->find();

            if ($admin && password_verify($password, $admin['password'])) {
                if ($admin['status'] == 2){
                    return json([
                        'code' => 501,
                        'msg' => '你账号已被禁用',
                    ]);
                }
                AdminLog::insert([
                    'name'  =>  $admin['username'],
                    'content'   =>  '管理员“'.$admin['username'].'”登录了系统。',
                    'type'  =>  1,
                    'ip'  =>  Request::ip(),
                    'create_time'   =>  time()
                ]);

                Session::set('admin_id', $admin['id']);
                Session::set('admin_name', $admin['username']);
//                Admins::where('id',Session('admin_id'))->update([
//                    'session'   =>  1
//                ]);
                Cache::set('admin_id', Session('admin_id'), 0);
                return json([
                    'code'  =>  200,
                    'msg'   =>  '登录成功!'
                ]);
            } else {
                // 验证失败，返回错误信息
                return json([
                    'code'  =>  201,
                    'msg'   =>  '用户名或密码错误!'
                ]);
            }
        }
    }

    public function PostSendItemUser(){
        $user = SenditemAuth::where('usn',session('USER_LOGIN_USN'))->find();
        if (!$user || $user['status'] != 1){
            return json([
                'code'  =>  207,
                'msg'   =>  '您的账号没有权限或者没有登陆'
            ]);
        }
        //发送物品
        $sql = "EXECUTE WSP_GIVE_ITEM @p_USN = ?, @p_GiveUSN = ?, @p_ID = ?, @p_Name = '', @p_Result = 0";
        Db::connect('CF_SA_WEB_DB')->execute($sql, [$user['usn'],$user['usn'], input('id')]);


        return json([
            'code'  =>  200,
            'msg'   =>  '发送成功'
        ]);
    }

    public function SendItemListUser()
    {
        //判断当前账号是否有权限
        $user = SenditemAuth::where('userid',session('USER_LOGIN_ID'))->find();

        if (!$user || $user['status'] != 1){
            return json([
                'code'  =>  207,
                'msg'   =>  '您的账号没有权限或者没有登陆'
            ]);
        }
        // 获取用户输入的分页参数和搜索文本
        $page = input('pageNumber', 1);
        $limit = input('pageSize', 20);
        $search = input('searchText');
        $sort = empty(input('sort')) ? 'ITEM_ID' : input('sort');

        // 构建数据库查询
        $query = Db::connect('game_db')->table('CF_ITEM_INFO')
            ->field('ITEM_ID, ITEM_CODE, NAME');

        // 如果用户提交了搜索文本，则添加搜索条件
        if ($search) {
            $query->where($sort, 'like', '%' . $search . '%');
        }

        // 执行分页查询并获取结果
        $list = $query->page($page, $limit)->select();

        // 准备返回的数据
        $json = [];
        foreach ($list as $item) {
            $json[] = [
                'ITEM_ID' => $item['ITEM_ID'],
                'ITEM_CODE' => $item['ITEM_CODE'],
                'NAME' => $item['NAME'],
            ];
        }

        // 获取满足搜索条件的总记录数（用于分页显示）
        $total = $query->count();

        // 以JSON格式返回查询结果和总记录数
        return json([
            'code' => 1,
            'total' => $total,
            'data' => $json,
        ]);
    }

    public function SendItemListUser1()
    {
        $page = input('pageNum', 1);
        $limit = input('pageSize', 20);
        $search = input('searchText');

        $list = Db::connect('game_db')->table('CF_ITEM_INFO')
            ->field('ITEM_ID, ITEM_CODE, NAME, REG_DATE')
            ->order('REG_DATE', 'DESC')
            ->page($page, $limit)
            ->select();
        $json = [];
        foreach ($list as $item) {
            $json[] = [
                'ITEM_ID' => $item['ITEM_ID'],
                'ITEM_CODE' => $item['ITEM_CODE'],
                'NAME' => $item['NAME'],
            ];
        }
        return json([
            'code' => 1,
            'total' => Db::connect('game_db')->table('CF_ITEM_INFO')->count(),
            'data' => $json,
        ]);
    }

    public function BuyShop(){
        $id = input('id');
        if (!session('USER_LOGIN_ID')){
            return json([
                'code'  =>  201,
                'msg'   =>  '您还没有登录买个毛线!'
            ]);
        }else{
            $userCf = Db::connect('game_db')->table('CF_USER')->where('USN', session('USER_LOGIN_USN'))
                ->find();
            if (!$userCf){
                return json([
                    'code' => 501,
                    'msg' => '请先创建角色后再进行购买！'
                ]);
            }

            $shop = Shop::where('id',$id)->find();

            if (!$shop){
                return json([
                    'code'  =>  202,
                    'msg'   =>  '参数错误'
                ]);
            }
            //购买逻辑
            if ($shop['type'] == 1){
                //cf点购买
                $Mst = Db::connect('G4BOX_SA_BILL_DB')->table('TAccountCashMst')->where('UserNo', $userCf['USN'])->find();
                if ($Mst['Cash'] < $shop['money']){
                    return json([
                        'code' => 502,
                        'msg' => 'CF余额不足购买此物品'
                    ]);
                }
                $money = $Mst['Cash'] - $shop['money'];
                $TOUTCash = $Mst['TOUTCash'] + $shop['money'];
                $formattedDate = date('Y-m-d H:i:s', time());
                Db::connect('G4BOX_SA_BILL_DB')->table('TAccountCashMst')->where('UserNo', $userCf['USN'])->update([
                    'Cash' => $money,
                    'TOUTCash' => $TOUTCash,
                    'UpdDate' => $formattedDate
                ]);
            } elseif ($shop['type'] ==  2) {
                //gp购买
                if ($userCf['GAME_POINT'] < $shop['money']){
                    return json([
                        'code' => 502,
                        'msg' => 'GP余额不足购买此物品'
                    ]);
                }
                $money = $userCf['GAME_POINT'] - $shop['money'];
                Db::connect('game_db')->table('CF_USER')->where('USN', $userCf['USN'])->update([
                    'GAME_POINT' => $money,
                ]);
            }
            $sql = "EXECUTE WSP_GIVE_ITEM @p_USN = ?, @p_GiveUSN = ?, @p_ID = ?, @p_Name = '', @p_Result = 0";
            Db::connect('CF_SA_WEB_DB')->execute($sql, [$userCf['USN'],$userCf['USN'], $shop['itemid']]);

            $sLog = new ShopLog();
            $sLog->sid = $id;
            $sLog->usn = session('USER_LOGIN_USN');
            $sLog->username = session('USER_LOGIN_ID');
            $sLog->create_time = time();
            $sLog->save();

            return json([
                'code' => 200,
                'msg' => '购买成功，已为你发送仓库！'
            ]);

        }
    }

    public function AjaxActivity(){
        //先判断是否登录成功
        $id = intval(input('id'));
        if (empty($id)){
            return json([
                'code'  =>  500,
                'msg'   =>  '参数不能为空'
            ]);
        }
        if (!session('USER_LOGIN_ID')){
            return json([
                'code'  =>  201,
                'msg'   =>  '请先登录后在尝试领取活动奖励'
            ]);
        }else{
            //先查角色
            $user = Db::connect('game_db')->table('CF_USER')
                ->where('USN',session('USER_LOGIN_USN'))
                ->find();
            if (!$user){
                return json([
                    'code'  =>  209,
                    'msg'   =>  '请先创建角色后在来领取奖励'
                ]);
            }
            //这里为登录成功后，判断奖励内容是什么
            $res = Events::where('id',$id)->find();

            //判断活动时间是否到期或者开启

            if (time() < $res['start_time']) {
                return json([
                    'code'  =>  207,
                    'msg'   =>  '该活动还没有开始，活动时间：'.date('Y-m-d H:i:s', $res['start_time'])
                ]);
            } elseif (time() > $res['end_time']) {
                return json([
                    'code'  =>  208,
                    'msg'   =>  '该活动以及结束啦！'
                ]);
            }

            if ($res['without'] == 2){
                return json([
                    'code'  =>  203,
                    'msg'   =>  '该活动没有所属奖励，为外部活动地址。'
                ]);
            }

            //判断活动是否领取过
            $log = EventsLog::where('eid',$id)->where('usn',session('USER_LOGIN_USN'))->where('username',session('USER_LOGIN_ID'))->find();
            if ($log){
                return json([
                    'code'  =>  202,
                    'msg'   =>  '您以及领取过该活动。'
                ]);
            }

            $type = $res['type']; // 假设$res是一个数组，并且包含键'type'

            switch ($type) {
                case 1:
                    // 当$type等于1时执行的代码
                    $result = Db::connect('CF_SA_WEB_DB') // 替换为你的表名
                    ->execute("EXECUTE WSP_GIVE_CURRENCY @p_USN = ?, @p_GiveUSN = ?, @p_Type = 'C', @p_Ammount = ?, @p_Result = 0", [session('USER_LOGIN_USN'), session('USER_LOGIN_USN'),$res['value']]);
                    $text = '领取成功，已为您赠送'.$res['value'].'CF点！';
                    if ($result !=0) {
                        // 处理执行失败的情况
                        return json(['code' => 500, 'msg' => '充值失败，请稍后再试！']);
                    }
                    break;
                case 2:
                    // 当$type等于2时执行的代码
                    $result = Db::connect('CF_SA_WEB_DB') // 替换为你的表名
                    ->execute("EXECUTE WSP_GIVE_CURRENCY @p_USN = ?, @p_GiveUSN = ?, @p_Type = 'G', @p_Ammount = ?, @p_Result = 0", [session('USER_LOGIN_USN'), session('USER_LOGIN_USN'),$res['value']]);
                    $text = '领取成功，已为您赠送'.$res['value'].'GP！';
                    if ($result !=0) {
                        // 处理执行失败的情况
                        return json(['code' => 500, 'msg' => '操作失败，请稍后再试！']);
                    }
                    break;
                case 3:
                    // 当$type等于3时执行的代码
                    $result =  Db::connect('CF_SA_WEB_DB') // 替换为你的表名
                    ->execute("EXECUTE WSP_GIVE_ITEM @p_USN = ?, @p_GiveUSN = ?, @p_ID = ?, @p_Name = '', @p_Result = 0", [session('USER_LOGIN_USN'), session('USER_LOGIN_USN'), $res['value']]);
                    $text = '领取成功，已为您赠送奖励物品！';
                    if ($result !=0) {
                        // 处理执行失败的情况
                        return json(['code' => 500, 'msg' => '操作失败，请稍后再试！']);
                    }
                    break;
                default:
                    // 当$type不等于1、2或3时执行的代码
                    echo "Type is not 1, 2, or 3";
                    break;
            }

            $eLog = new EventsLog();
            $eLog->eid = $id;
            $eLog->usn = session('USER_LOGIN_USN');
            $eLog->username = session('USER_LOGIN_ID');
            $eLog->create_time = time();
            $eLog->save();
            return json([
                'code'  =>  200,
                'msg'   =>  $text
            ]);

        }
    }

    public function PostReport(){
        if (!session('USER_LOGIN_ID')) {
            return json([
                'code'  =>  201,
                'msg'   =>  '请先登录在进行操作'
            ]);
        }
//        if (empty(input('reportType')) || empty(input('reportedNickname')) || empty(input('content') || empty(input('appealAccount')) | empty(input('title')))){
//            return json([
//                'code' => 501,
//                'msg' => '所有选项都不能为空 ！'
//            ]);
//        }
        if(!empty(input('reportedNickname'))){
            $user = Db::connect('game_db')->table('CF_USER')
                ->where('NICK', 'like', '%'.input('reportedNickname').'%')
                ->find();
            if (!$user){
                return json([
                    'code' => 504,
                    'msg' => '没有查询到该角色，请核对昵称是否正确 ！'
                ]);
            }
        }

        $rusn = !empty(input('reportedNickname')) ? $user['USN'] : 0;

        $user = Db::connect('game_db')->table('CF_MEMBER')
            ->where('USN',session('USER_LOGIN_USN'))
            ->find();
        $Report = new Report();
        $Report->usn = $user['USN'];
        $Report->rusn = $rusn;
        $Report->title = input('title');
        $Report->reportedNickname = input('reportedNickname');
        $Report->appealAccount = $user['USER_ID'];
        $Report->reportType = input('reportType');
        $Report->content = input('content');
        $Report->status = 0;
        $Report->aid = 0;
        $Report->create_time = time();
        $Report->save();
        return json([
            'code'  =>  200,
            'msg'   =>  '提交成功'
        ]);
    }
}