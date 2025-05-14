<?php
/**
 *
 * @Author: 会飞的鱼
 * @Date: 2024/7/23 0023
 * @Time: 14:55
 * @Blog：https://houz.cn/
 * @Description: 会飞的鱼作品,禁止修改版权违者必究。
 */


namespace app\controller;


use app\model\AdminLog;
use app\model\AgencyCode;
use app\model\AgencyMoneyLog;
use app\model\AgencyNickCdk;
use app\model\AgencyOrder;
use app\model\AgencyShop;
use app\model\AgencyShopCdk;
use app\model\AgencyUser;
use app\model\AgencyUserLog;
use app\model\Configs;
use app\model\UserLog;
use think\facade\Config;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use Epay\EpayCore;
use think\Model;

class PostAgency
{
    public function login(){
        $username = input('username');
        $password = input('password');
        //$code = input('code');
        $code = request()->param('AgenCaptcha');
        if (!captcha_check($code)) {
            return json([
                'code'  =>  203,
                'msg'   =>  '验证码错误!'
            ]);
        }
        if (empty($username) || empty($password) || empty($code)){
            return json([
                'code'  =>  201,
                'msg'   =>  '账号密码或者验证码不能为空'
            ]);
        }
        $user = AgencyUser::where('user', $username)->find();
        if ($user && password_verify($password, $user['pass'])) {
            Session::set('Agency_LOGIN_ID', $user['id']);
            Session::set('Agency_LOGIN_name', $user['user']);
            return json([
                'code'  =>  200,
                'msg'   =>  '登录成功!'
            ]);

        }else{
            return json([
                'code'  =>  203,
                'msg'   =>  '用户名或密码错误!'
            ]);
        }
    }


    public function pay(){
        $yipayConfig = Config::get('epay');
        $notify_url = Request::domain()."/PayAjax/notify";
        $return_url = Request::domain()."/PayAjax/return";
        $out_trade_no = date("YmdHis") . mt_rand(100, 999);
        $type = input('type');
        $name = '余额充值';
        $money = input('money');
        $parameter = array(
            "pid" => 1004,
            "type" => $type,
            "notify_url" => $notify_url,
            "return_url" => $return_url,
            "out_trade_no" => $out_trade_no,
            "name" => $name,
            "money"	=> $money,
        );

        $epay = new EpayCore($yipayConfig);
        $html_text = $epay->getPayLink($parameter);



        if (!empty($html_text)){
            AgencyOrder::insert([
                'uid'   =>  session('Agency_LOGIN_ID'),
                'out_trade_no'  =>  $out_trade_no,
                'trade_no'  =>  '',
                'trade_status'  =>  0,
                'type'  =>  $type,
                'money'  =>  $money,
                'create_time'  =>  time(),
                'update_time'   =>  ''
            ]);
            return json([
                'code'  =>  200,
                'url'   =>  $html_text
            ]);
        }else{
            return json([
                'code'  =>  500,
                'msg'   =>  '服务器异常'
            ]);
        }
    }

    public function notify(){

    }

    public function return(){
        //回调函数
        $yipayConfig = Config::get('epay');
        $epay = new EpayCore($yipayConfig);
        $verify_result = $epay->verifyReturn();
        if ($verify_result){
            //商户订单号
            $out_trade_no = input('out_trade_no');

            //彩虹易支付交易号
            $trade_no = input('trade_no');

            //交易状态
            $trade_status = input('trade_status');

            //支付方式
            $type = input('type');

            //支付金额
            $money = input('money');

            if (input('trade_status') == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
            }

            AgencyOrder::where('uid',session('Agency_LOGIN_ID'))->where('out_trade_no',$out_trade_no)->update([
                'trade_no'  =>  $trade_no,
                'trade_status'  =>    $trade_status,
                'update_time'   =>  time()
            ]);
            AgencyUser::where('id',session('Agency_LOGIN_ID'))->inc('money', $money)->update();

            //验证成功返回
            return redirect("/Agency/pay");
//            return json([
//                'code'  =>  200,
//                'msg'   =>  '支付成功'
//            ]);
        }else{
            return json([
                'code'  =>  500,
                'msg'   =>  '支付没有完成'
            ]);
        }
    }

    public function BuyItem(){
        if (session('Agency_LOGIN_ID')){
            if (empty(input('id')) || empty(input('user'))){
                return json([
                    'code'  =>  202,
                    'msg'   =>  '参数不能为空'
                ]);
            }
            $user = AgencyUser::where('id',session('Agency_LOGIN_ID'))->find();
            $shop = AgencyShop::where('id',input('id'))->find();
            $userlog = AgencyUserLog::where('user',input('user'))->find();

            $db = Db::connect('game_db');
            $result = $db->table('CF_MEMBER')->where('USER_ID', input('user'))
                ->find();

            if ($result){

                if ($userlog){

                    if ($userlog['uid'] != session('Agency_LOGIN_ID')){
                        return json([
                            'code'  =>  203,
                            'msg'   =>  '当前用户不属于你邀请用户'
                        ]);
                    }

                }else{
                    return json([
                        'code'  =>  203,
                        'msg'   =>  '当前用户不属于你邀请用户'
                    ]);
                }
            }else{
                return json([
                    'code'  =>  207,
                    'msg'   =>  '用户不存在'
                ]);
            }
            if ($user['money'] < $shop['money']){
                return json([
                    'code'  =>  201,
                    'msg'   =>  '当前余额不足购买此物品'
                ]);
            }
            AgencyUser::where('id',session('Agency_LOGIN_ID'))->dec('money', $shop['money'])->update();

            AgencyMoneyLog::insert([
                'uid'   =>  session('Agency_LOGIN_ID'),
                'money' =>  $shop['money'],
                'user'  =>  '发送给了'.input('user'),
                'title' =>  '购买物品：'.$shop['title'],
                'create_time'   =>  time()
            ]);

            $sql = "EXECUTE WSP_GIVE_ITEM @p_USN = ?, @p_GiveUSN = ?, @p_ID = ?, @p_Name = '', @p_Result = 0";
            Db::connect('CF_SA_WEB_DB')->execute($sql, [$result['USN'],$result['USN'], $shop['itemid']]);

            return json([
                'code'  =>  200,
                'msg'   =>  '购买成功！已为您发送物品给账号。'
            ]);
        }else{
            return json([
                'code'  =>  500,
                'msg'   =>  '您还没有登录'
            ]);
        }
    }

    public function generateCdk(){
        if (session('Agency_LOGIN_ID')){
            if (preg_match('/，/', input('ids'))) {
                return json([
                    'code' => 400,
                    'msg' => '请使用英文逗号分隔ID。'
                ]);
            }
            if (!is_numeric(input('num')) || input('num') > 100) {
                return json([
                    'code'  =>  203,
                    'msg'   =>  '数量必须为数字且不大于20'
                ]);
            }
            $ids = explode(',',input('ids'));
            $totalPrice = 0;
            foreach ($ids as $v){
                $res = AgencyShop::where('id',$v)->find();
                if (!$res){
                    return json([
                        'code'  =>  201,
                        'msg'   =>  'ID：'.$v.' 不正确，请认真检查'
                    ]);
                }
                $totalPrice += $res['money'];
            }
            $money = $totalPrice * input('num');
            $user = AgencyUser::where('id',session('Agency_LOGIN_ID'))->find();
            if ($user['money'] < $money){
                return json([
                    'code'  =>  202,
                    'msg'   =>  '当前生成Cdk需要'.$money.'元，您当前余额不足以扣除'
                ]);
            }



            AgencyUser::where('id',session('Agency_LOGIN_ID'))->dec('money', $money)->update();

            $cdKeys = [];
            $saveData = [];
            for ($start = 0; $start < input('num'); $start++) {
                $cdKeys[$start] = generateSurvivalCDK(32);
                $saveData[$start]['uid'] = session('Agency_LOGIN_ID');
                $saveData[$start]['username'] = '';
                $saveData[$start]['itemid'] = input('ids');
                $saveData[$start]['status'] = 0;
                $saveData[$start]['code'] = $cdKeys[$start];
            }
            $cacheKey = md5(uniqid());

            cache("showNewShopCdkCache:" . $cacheKey, $cdKeys, 180);
            (new AgencyShopCdk())->saveAll($saveData);

            AgencyMoneyLog::insert([
                'uid'   =>  session('Agency_LOGIN_ID'),
                'money' =>  $money,
                'user'  =>  '生成了'.input('num').'张CDK',
                'title' =>  '生成物品CDK',
                'create_time'   =>  time()
            ]);

            return json([
                'code'  =>  200,
                'msg'   =>  '恭喜您生成了'.input('num').'个CDK，消费了'.$money.'元'
            ]);

        }else{
            return json([
                'code'  =>  500,
                'msg'   =>  '您还没有登录'
            ]);
        }
    }

    public function AddPostNick(){
        if (session('Agency_LOGIN_ID')){
            if (empty(input('code'))){
                return json([
                    'code'  =>  201,
                    'msg'   =>  '所有输入框都不能为空'
                ]);
            }
            $Config = Configs::gets();
            $money = $Config['agency_nick'] * input('code');
            $user = AgencyUser::where('id',session('Agency_LOGIN_ID'))->find();
            if ($user['money'] < $money){
                return json([
                    'code'  =>  202,
                    'msg'   =>  '当前生成邀请码需要'.$money.'元，您当前余额不足以扣除'
                ]);
            }

            AgencyUser::where('id',session('Agency_LOGIN_ID'))->dec('money', $money)->update();
            $number = input('code');
            $cdKeys = [];
            $saveData = [];


            for ($start = 0; $start < $number; $start++) {
                $cdKeys[$start] = generateSurvivalCDK(32);
                $saveData[$start]['uid'] = session('Agency_LOGIN_ID');
                $saveData[$start]['username'] = '';
                $saveData[$start]['status'] = 0;
                $saveData[$start]['code'] = $cdKeys[$start];
            }
            $cacheKey = md5(uniqid());

            cache("showNewNickCache:" . $cacheKey, $cdKeys, 180);
            (new AgencyNickCdk())->saveAll($saveData);

            AgencyMoneyLog::insert([
                'uid'   =>  session('Agency_LOGIN_ID'),
                'money' =>  $money,
                'user'  =>  '生成了'.$number.'张改名卡',
                'title' =>  '生成改名卡',
                'create_time'   =>  time()
            ]);
            return json([
                'code'  =>  200,
                'msg'   =>  '生成成功'
            ]);
        }else{
            return json([
                'code'  =>  500,
                'msg'   =>  '您还没有登录'
            ]);
        }
    }

    public function AddPostCode(){

        if (session('Agency_LOGIN_ID')){
            if (empty(input('code'))){
                return json([
                    'code'  =>  201,
                    'msg'   =>  '所有输入框都不能为空'
                ]);
            }
            $Config = Configs::gets();
            $money = $Config['agency_inv'] * input('code');
            $user = AgencyUser::where('id',session('Agency_LOGIN_ID'))->find();
            if ($user['money'] < $money){
                return json([
                    'code'  =>  202,
                    'msg'   =>  '当前生成邀请码需要'.$money.'元，您当前余额不足以扣除'
                ]);
            }

            AgencyUser::where('id',session('Agency_LOGIN_ID'))->dec('money', $money)->update();
            $number = input('code');
            $cdKeys = [];
            $saveData = [];


            for ($start = 0; $start < $number; $start++) {
                $cdKeys[$start] = generateSurvivalCDK(32);
                $saveData[$start]['uid'] = session('Agency_LOGIN_ID');
                $saveData[$start]['username'] = '';
                $saveData[$start]['status'] = 0;
                $saveData[$start]['code'] = $cdKeys[$start];
            }
            $cacheKey = md5(uniqid());

            cache("showNewagencycodeCache:" . $cacheKey, $cdKeys, 180);
            (new AgencyCode())->saveAll($saveData);

            AgencyMoneyLog::insert([
                'uid'   =>  session('Agency_LOGIN_ID'),
                'money' =>  $money,
                'user'  =>  '生成了'.$number.'张邀请码',
                'title' =>  '生成注册邀请码',
                'create_time'   =>  time()
            ]);
            return json([
                'code'  =>  200,
                'msg'   =>  '生成成功'
            ]);
        }else{
            return json([
                'code'  =>  500,
                'msg'   =>  '您还没有登录'
            ]);
        }
    }

    public function AddPostUser(){
        if (session('Agency_LOGIN_ID')){
            if (empty(input('email')) || empty(input('user')) || empty(input('pass'))){
                return json([
                    'code'  =>  201,
                    'msg'   =>  '所有输入框都不能为空'
                ]);
            }
            $db = Db::connect('game_db');
            $result = $db->table('CF_MEMBER')->where('USER_ID', input('user'))
                ->whereOr('EMAIL', input('email'))
                ->find();

            if ($result){
                if ($result['USER_ID'] == input('user')){
                    return json([
                        'code' => 502,
                        'msg' => '账号已存在'
                    ]);
                }

                if ($result['EMAIL'] == input('email')){
                    return json([
                        'code' => 503,
                        'msg' => '邮箱已存在'
                    ]);
                }
            }

            $sql = "EXECUTE PROC_WEB_USER_INFO_INS @p_User = ?, @p_User_pass = ? , @p_Mail = ? ,@p_Result=0";
            Db::connect('game_db')->execute($sql, [input('user'), input('pass'),input('email')]);



            //存入全局注册记录
            $userLog = new UserLog();
            $userLog->userid = input('user');
            $userLog->email = input('email');
            $userLog->create_time = time();
            $userLog->ip = request()->ip();
            $userLog->Invite = 0;
            $userLog->type = 1;
            $userLog->save();
            //存入代理注册记录
            $log = new AgencyUserLog();
            $log->uid = session('Agency_LOGIN_ID');
            $log->user = input('user');
            $log->pass = input('pass');
            $log->email = input('email');
            $log->create_time = time();
            $log->save();
            return json([
                'code'  =>  200,
                'msg'   =>  '注册成功'
            ]);
        }else{
            return json([
                'code'  =>  500,
                'msg'   =>  '您还没有登录'
            ]);
        }
    }

    public function edit_user(){
        if (session('Agency_LOGIN_ID')){
            if (empty(input('pass'))){
                return json([
                    'code'  =>  201,
                    'msg'   =>  '密码不能为空'
                ]);
            }
            AgencyUser::where('id',session('Agency_LOGIN_ID'))->update(['pass'=>password_hash(input('pass'), PASSWORD_DEFAULT)]);
            session::delete('Agency_LOGIN_ID');
            session::delete('Agency_LOGIN_name');
            return json([
                'code'  =>  200,
                'msg'   =>  '修改成功'
            ]);
        }else{
            return json([
                'code'  =>  500,
                'msg'   =>  '您还没有登录'
            ]);
        }
    }

    public function newNickname(){
        if (session('Agency_LOGIN_ID')){
            if (empty(input('usn')) || empty(input('newNickname'))){
                return json([
                    'code'  =>  201,
                    'msg'   =>  '参数不能为空'
                ]);
            }
            if (!preg_match('/^[\x{4e00}-\x{9fa5}]{3,5}$/u', input('newNickname'))){
                return json([
                    'code'  =>  203,
                    'msg'   =>  '修改昵称只能是中文，不能且只允许3-5个中文字符！'
                ]);
            }
            $user = Db::connect('game_db')->table('CF_USER')->where('USN',input('usn'))->find();
            if ($user){
                Db::connect('game_db')->table('CF_USER')->where('USN',input('usn'))->update([
                    'NICK'  =>  iconv("ISO-8859-1", "UTF-8", iconv("UTF-8", "GB18030", input('newNickname'))),
                ]);
                return json([
                    'code'  =>  200,
                    'msg'   =>  '修改成功'
                ]);
            } else {
                return json([
                    'code'  =>  202,
                    'msg'   =>  '当前账号没有创建角色'
                ]);
            }
        } else{
            return json([
                'code'  =>  500,
                'msg'   =>  '您还没有登录'
            ]);
        }
    }

    public function saveLevel(){
        if (session('Agency_LOGIN_ID')){
            if (empty(input('userId')) || empty(input('newLevel'))){
                return json([
                    'code'  =>  201,
                    'msg'   =>  '参数不能为空'
                ]);
            }
            if (!is_numeric(input('newLevel')) || input('newLevel') > 100) {
                return json([
                    'code'  =>  203, // 假设我们用一个新的code来表示这个错误
                    'msg'   =>  '等级必须为数字且不大于100'
                ]);
            }
            $user = Db::connect('game_db')->table('CF_USER')->where('USN',input('userId'))->find();
            if ($user){
                Db::connect('game_db')->table('CF_USER')->where('USN',input('userId'))->update([
                    'LEV'  =>  input('newLevel'),
                ]);
                return json([
                    'code'  =>  200,
                    'msg'   =>  '修改成功'
                ]);
            } else {
                return json([
                    'code'  =>  202,
                    'msg'   =>  '当前账号没有创建角色'
                ]);
            }
        } else{
            return json([
                'code'  =>  500,
                'msg'   =>  '您还没有登录'
            ]);
        }
    }

    public function TransferUser(){
        if (session('Agency_LOGIN_ID')){
            $userInfo = input('userInfo');
            // 获取要更新的ID数组
            $ids = input('ids');

            // 检查是否提供了用户信息和ID数组
            if (empty($userInfo) || empty($ids)) {
                return json([
                    'code' => 201,
                    'msg' => '用户信息或ID数组不能为空'
                ]);
            }

            // 查找目标用户
            $od = AgencyUser::where('user', $userInfo)->find();
            if (!$od) {
                return json([
                    'code' => 202,
                    'msg' => '代理账号不存在'
                ]);
            }
            $updateSuccess = true;
            foreach ($ids as $id) {
                // 确保ID是数字
                if (!is_numeric($id)) {
                    $updateSuccess = false;
                    break;
                }

                //var_dump($id);

                // 执行更新操作
                $result = AgencyUserLog::where('id', $id)->update(['uid' => $od['id']]);
                if (!$result) {
                    $updateSuccess = false;
                    break;
                }
            }

            // 根据更新操作的结果返回信息
            if ($updateSuccess) {
                return json([
                    'code' => 200,
                    'msg' => '转移成功'
                ]);
            } else {
                return json([
                    'code' => 203,
                    'msg' => '转移出错'
                ]);
            }
        }else{
            return json([
                'code'  =>  500,
                'msg'   =>  '您还没有登录'
            ]);
        }
    }

    public function gift_cf(){
        if (session('Agency_LOGIN_ID')){
            if (empty(input('user')) || empty(input('cf'))){
                return json([
                    'code'  =>  201,
                    'msg'   =>  '所有选项不能为空'
                ]);
            }
            $res = AgencyUserLog::where('user',input('user'))->find();
            if (empty($res)){
                return json([
                    'code'  =>  202,
                    'msg'   =>  '没有找到该账号'
                ]);
            }
            if ($res['uid'] != session('Agency_LOGIN_ID')){
                return json([
                    'code'  =>  202,
                    'msg'   =>  '抱歉，无法赠送非名下邀请用户'
                ]);
            }
            if (!is_numeric(input('cf')) || input('cf') < 0) {
                return json([
                    'code'  =>  203,
                    'msg'   =>  '数量必须为数字且不能小于0'
                ]);
            }
            $user = AgencyUser::where('id',session('Agency_LOGIN_ID'))->find();
            if ($user['cf'] < input('cf')){
                return json([
                    'code'  =>  204,
                    'msg'   =>  '当前余额不足以扣除'
                ]);
            }
            AgencyUser::where('id',session('Agency_LOGIN_ID'))->dec('cf', input('cf'))->update();
            $result = Db::connect('game_db')->table('CF_MEMBER')->where('USER_ID', input('user'))
                ->find();
            $sql = "EXECUTE WSP_GIVE_CURRENCY @p_USN = ?, @p_GiveUSN = ?, @p_Type = 'C', @p_Ammount = ?, @p_Result = 0";

            Db::connect('CF_SA_WEB_DB')->execute($sql, [$result['USN'],$result['USN'],input('cf')]);
            AgencyMoneyLog::insert([
                'uid'   =>  session('Agency_LOGIN_ID'),
                'money' =>  input('cf'),
                'user'  =>  '给予账号'.input('user').'赠送了'.input('cf').'点',
                'title' =>  '赠送CF点',
                'create_time'   =>  time()
            ]);
            return json([
                'code'  =>  200,
                'msg'   =>  '赠送成功'
            ]);
        }else{
            return json([
                'code'  =>  500,
                'msg'   =>  '您还没有登录'
            ]);
        }
    }
}