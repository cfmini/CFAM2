<?php
/**
 *
 * @Author: 会飞的鱼
 * @Date: 2024/4/5 0005
 * @Time: 15:08
 * @Blog：https://houz.cn/
 * @Description: 会飞的鱼作品,禁止修改版权违者必究。
 */


namespace app\controller;


use app\middleware\LoginStatu;
use app\model\AgencyNickCdk;
use app\model\Cdks;
use app\model\Configs;
use app\model\UserLog;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;

class UserPost
{
    protected $middleware = [
        LoginStatu::class,
    ];

    public function EmailPost()
    {
        $email = input('post.email');
        if (empty($email)) {
            return json([
                'code' => 101,
                'msg' => '邮箱不能为空'
            ]);
        }
        $UserLog = UserLog::where('userid', session('USER_LOGIN_ID'))->find();
        if (empty($UserLog)) {
            return json([
                'code' => 500,
                'msg' => '当前没有绑定邮箱'
            ]);
        }
        if ($email == $UserLog['email']) {
            return json([
                'code' => 102,
                'msg' => '邮箱已存在，请更换其它邮箱'
            ]);
        }
        if ($UserLog) {
            $res = UserLog::where('userid', session('USER_LOGIN_ID'))->update(['email' => $email]);
            Db::connect('game_db')->table('CF_MEMBER')->where('USER_ID', session('USER_LOGIN_ID'))->update(['EMAIL' => $email]);
            if ($res) {
                return json([
                    'code' => 200,
                    'msg' => '修改成功'
                ]);
            }
        }
    }

    public function AddEmail()
    {
        $UserLog = UserLog::where('userid', session('USER_LOGIN_ID'))->find();
        if (empty($UserLog)) {
            $UserLog = UserLog::where('email', input('email'))->find();
            if ($UserLog) {
                return json([
                    'code' => 501,
                    'msg' => '当前邮箱已被绑定'
                ]);
            }
            Db::connect('game_db')->table('CF_MEMBER')->where('USER_ID', session('USER_LOGIN_ID'))->update(['EMAIL' => input('email')]);
            UserLog::insert([
                'email' => input('email'),
                'userid' => session('USER_LOGIN_ID'),
                'ip' => Request::ip(),
                'Invite' => 0,
                'type' => 0,
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '添加成功'
            ]);
        } else {
            Db::connect('game_db')->table('CF_MEMBER')->where('USER_ID', session('USER_LOGIN_ID'))->update(['EMAIL' => input('email')]);
            UserLog::where('userid', session('USER_LOGIN_ID'))->update([
                'email' => input('email'),
            ]);
            return json([
                'code' => 200,
                'msg' => '修改成功'
            ]);
        }
    }

    public function CkNamePost()
    {
        $ckname = input('post.ckname');
        $Config = Configs::gets();
        if (preg_match('/^[\x{4e00}-\x{9fa5}]{3,5}$/u', $ckname)) {

            $user = Db::connect('game_db')->table('CF_USER')->where('USN', session('USER_LOGIN_USN'))->field('NICK,GAME_POINT')->find();
            if (empty($user)) {
                return json([
                    'code' => 501,
                    'msg' => '请先创建角色'
                ]);
            }
            $res = Db::connect('game_db')->table('CF_USER')->where('NICK', iconv("ISO-8859-1", "UTF-8", iconv("UTF-8", "GB18030", $ckname)))->field('NICK')->find();
            if ($res > 0) {
                return json([
                    'code' => 503,
                    'msg' => '当前昵称已被使用'
                ]);
            }
            $money = $Config['cknamemoney'];
            if ($user['GAME_POINT'] < $money) {
                return json([
                    'code' => 502,
                    'msg' => 'GP不足，修改一次需要' . $money . 'GP'
                ]);
            }

            Db::connect('game_db')->table('CF_USER')
                ->where('USN', session('USER_LOGIN_USN'))
                ->update([
                    'NICK' => iconv("ISO-8859-1", "UTF-8", iconv("UTF-8", "GB18030", $ckname)),
                    'GAME_POINT' => $user['GAME_POINT'] - $money
                ]);

            return json([
                'code' => 200,
                'msg' => '修改成功！'
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '修改昵称只能是中文，不能且只允许3-5个中文字符！'
            ]);
        }
    }

    public function NamePost()
    {
        $name = input('post.name');
        $cdk = input('post.cdk');
        if (empty($cdk)) {
            return json([
                'code' => 504,
                'msg' => '改名卡Cdk不能为空'
            ]);
        }
        $cdk = AgencyNickCdk::where('code',$cdk)->find();

        if (empty($cdk)){
            return json([
                'code' => 506,
                'msg' => 'Cdk不正确'
            ]);
        }
        if ($cdk['status'] != 0){
            return json([
                'code' => 507,
                'msg' => 'Cdk已被使用'
            ]);
        }

        if (preg_match('/^[\x{4e00}-\x{9fa5}]{1,6}$/u', $name)) {

            $user = Db::connect('game_db')->table('CF_USER')->where('USN', session('USER_LOGIN_USN'))->field('NICK,GAME_POINT')->find();
            if (empty($user)) {
                return json([
                    'code' => 501,
                    'msg' => '请先创建角色'
                ]);
            }
            $res = Db::connect('game_db')->table('CF_USER')->where('NICK', iconv("ISO-8859-1", "UTF-8", iconv("UTF-8", "GB18030", $name)))->field('NICK')->find();
            if ($res > 0) {
                return json([
                    'code' => 503,
                    'msg' => '当前昵称已被使用'
                ]);
            }

            Db::connect('game_db')->table('CF_USER')
                ->where('USN', session('USER_LOGIN_USN'))
                ->update([
                    'NICK' => iconv("ISO-8859-1", "UTF-8", iconv("UTF-8", "GB18030", $name)),
                ]);

            AgencyNickCdk::where('code',$cdk['code'])->update(['status'=>1,'username'=>session('USER_LOGIN_ID')]);


            return json([
                'code' => 200,
                'msg' => '修改成功！'
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '修改昵称只能是中文，不能且只允许3-5个中文字符！'
            ]);
        }
    }

    public function PostPass()
    {
        $OldPassword = input('post.OldPassword');
        $NewPassword = input('post.NewPassword');
        $ConfirmPassword = input('post.ConfirmPassword');
        if ($NewPassword != $ConfirmPassword) {
            return json([
                'code' => 103,
                'msg' => '您的新密码与确认密码不一致！'
            ]);
        }
        $db = Db::connect('game_db');
        $result = $db->table('CF_MEMBER')
            ->where('USER_ID', session('USER_LOGIN_ID'))
            ->whereRaw("USER_PASS = HASHBYTES('SHA1', '{$OldPassword}')")
            ->find();

        if ($result) {
            $db->table('CF_MEMBER')->where('USER_ID', session('USER_LOGIN_ID'))->update([
                'USER_PASS' => Db::raw("HASHBYTES('SHA1', '{$NewPassword}')"),
            ]);
            session::delete('USER_LOGIN_ID');
            session::delete('USER_LOGIN_USN');

            return json([
                'code' => 200,
                'msg' => '密码修改成功'
            ]);
        } else {
            return json([
                'code' => 201,
                'msg' => '旧密码不正确'
            ]);
        }
    }

    public function PostCdk()
    {
        $cdk = input('cdk');
        if (empty($cdk)) {
            return json([
                'code' => 500,
                'msg' => '请输入Cdk'
            ]);
        }

        $res = Cdks::where('code', $cdk)->find();
        if (!$res || $res['status'] == 1) {
            return json([
                'code' => ($res ? 501 : 500),
                'msg' => ($res ? 'Cdk已被使用' : 'Cdk不存在')
            ]);
        }


        $itemsStr = $res['item'];
        $items = explode(',', $itemsStr);
        try {
            Db::connect('CF_SA_WEB_DB')->startTrans();
            foreach ($items as $itemId) {
                $result = Db::connect('CF_SA_WEB_DB')->execute("EXECUTE WSP_GIVE_ITEM @p_USN = ?, @p_GiveUSN = ?, @p_ID = ?, @p_Name = '', @p_Result = 0", [
                    session('USER_LOGIN_USN'),
                    session('USER_LOGIN_USN'),
                    $itemId,
                ]);

                if ($result != 0) {
                    Db::connect('CF_SA_WEB_DB')->rollback();
                    return json([
                        'code' => 502,
                        'msg' => '发送失败：' . $itemId
                    ]);
                }
            }
            Db::connect('game_db')->commit();
            if ($res['type'] == 1) {
                Cdks::where('code', $cdk)->update(['status' => 1, 'name' => \session('USER_LOGIN_ID')]);
            }
            return json([
                'code' => 200,
                'msg' => '发送成功'
            ]);

        } catch (\Exception $e) {
            Db::connect('game_db')->rollback();
            return json([
                'code' => 503,
                'msg' => '发送过程中发生错误：' . $e->getMessage()
            ]);
        }
    }
}