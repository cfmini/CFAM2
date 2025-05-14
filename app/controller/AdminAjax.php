<?php
/**
 *
 * @Author: 会飞的鱼
 * @Date: 2024/4/14 0014
 * @Time: 9:53
 * @Blog：https://houz.cn/
 * @Description: 会飞的鱼作品,禁止修改版权违者必究。
 */


namespace app\controller;


use app\middleware\AdminStatus;
use app\model\AdminLog;
use app\model\Admins;
use app\model\AgencyCode;
use app\model\AgencyUser;
use app\model\BatchSendLog;
use app\model\Cdks;
use app\model\Configs;
use app\model\Events;
use app\model\EventsLog;
use app\model\Invitation;
use app\model\InviteLog;
use app\model\News;
use app\model\Report;
use app\model\SenditemAuth;
use app\model\Shop;
use app\model\ShopLog;
use app\model\UserLog;
use app\validate\UserV;
use think\facade\Db;
use think\facade\Session;
use think\Exception;
use think\Model;
use think\Validate;
use think\facade\Request;
use think\facade\Filesystem;
use GuzzleHttp\Client;
use ZipArchive;

class AdminAjax
{
    protected $middleware = [
        AdminStatus::class,
    ];

    public function UserList()
    {
        $page = (int)input('page', '1', 'trim');
        $limit = (int)input('limit', '10', 'trim');
        $keyword = input('username'); // 获取搜索关键字

        $query = Db::connect('game_db')->table('CF_MEMBER')
            ->limit(($page - 1) * $limit, $limit)
            ->order('USN', 'DESC');
        if (!empty($keyword)) {
            $query->where('USER_ID', 'like', '%' . $keyword . '%');
        }
        $result = $query->select();

        $count = Db::connect('game_db')->table('CF_MEMBER');
        if (!empty($keyword)) {
            $count->where('USER_ID', 'like', '%' . $keyword . '%'); // 添加模糊搜索条件
        }
        $count = $count->count();

        $json = [];
        foreach ($result as $k => $v) {

            $user = Db::connect('game_db')->table('CF_USER')->where('USN', $v['USN'])->find();

            set_error_handler(function($errno, $errstr, $errfile, $errline) {
            });
            $nickss = iconv("GB18030", "UTF-8", iconv("UTF-8", "ISO-8859-1", $user['NICK']));
            restore_error_handler();
            if (empty($user)){
                $nick = '<font color="red">无角色</font>';
            }elseif(empty($nickss)){
                $nick = $user['NICK'];
            }else{
                $nick = $nickss;
            }
            $ban = empty($user) ? 'M' : $user['HOLD_TYPE'];

            if ($ban == 'A') {
                $HOLD_TYPE = 1;
            } elseif ($ban == 'E') {
                $HOLD_TYPE = 0;
            } else {
                $HOLD_TYPE = 2;
            }
            $userId = $v['USER_ID'];
            $json[$k] = [
                'usn' => $v['USN'],
                'nick' => $nick,
                'account' => $userId,
                'HOLD_TYPE' => $HOLD_TYPE,
            ];

        }
        return json([
            'code' => 0,
            'count' => $count,
            'data' => $json
        ]);
    }

    public function resetPassword(\think\Request $request)
    {
        try {
            validate(UserV::class)->scene('setPassword')->check(input());
            if (!password_verify(input('old_password'), $request->User['password'])) {
                return json(['code' => 2001, 'msg' => '旧密码不匹配']);
            }
            Admins::update([
                'password' => password_hash(input('password'), PASSWORD_BCRYPT)
            ], [
                'id' => $request->User['id']
            ]);
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”修改了自己的密码',
                'type' => 2,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            session::delete('admin_id');
            return json(['code' => 200, 'msg' => '密码已修改成功']);
        } catch (ValidateException $exception) {
            return json([
                'code' => 5001,
                'msg' => $exception->getMessage()
            ]);
        }
    }

    public function UserInfo()
    {
        $userinfo = Db::connect('game_db')->table('CF_USER')->where('USN', input('id'))->find();

        return json([
            'code' => 200,
            'data' => [
                'usn' => 1
            ]
        ]);
    }

    public function showEditModel()
    {
        //用户个人信息
        $userinfo = Db::connect('game_db')->table('CF_USER')->where('USN', input('id'))->find();
        if (empty($userinfo)) {
            return json([
                'code' => 500,
                'msg' => '该账号还没有创建角色'
            ]);
        }
        //用户CF点卷表
        $mst = Db::connect('G4BOX_SA_BILL_DB')->table('TAccountCashMst')->where('UserNo', $userinfo['USN'])
            ->field('Cash')
            ->find();


        $CashReal = empty($mst) ? 0 : $mst['Cash'];
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
        });
        $nickss = iconv("GB18030", "UTF-8", iconv("UTF-8", "ISO-8859-1", $userinfo['NICK']));
        restore_error_handler();
        $userinfo['NICK'] = empty($nickss) ? $userinfo['NICK']:$nickss;
        return json([
            'code' => 200,
            'data' => [
                'usn' => $userinfo['USN'],
                'nick' => $userinfo['NICK'],
                'lev' => $userinfo['LEV'],
                'exp' => $userinfo['EXP'],
                'type' => $userinfo['HOLD_TYPE'],
                'gp' => $userinfo['GAME_POINT'],
                'cf' => $CashReal
            ]
        ]);
    }

    public function EditUser()
    {
        // 假设您有一个用于获取输入的函数input()
        $usn = input('usn');
        $nick = input('nick');
        $lev = input('lev');
        $exp = input('exp');
        $type = input('type');
        $gp = input('gp');
        $cf = input('cf');
        $cfs = input('cfs'); // 假设这是旧值，用于比较
        if (empty($usn)) {
            return json([
                'code' => 500,
                'msg' => 'USN不能为空'
            ]);
        }
        $requiredFields = ['nick', 'lev', 'exp', 'type', 'gp', 'cf'];
        $nullFields = [];
        foreach ($requiredFields as $field) {
            $fieldValue = input($field);
            if ($fieldValue === null) {
                $nullFields[] = $field; // 将为null的字段添加到数组中
            }
        }
        if (!empty($nullFields)) {
            return json([
                'code' => 500,
                'msg' => '所有选项不能为空'
            ]);
        }
        if ($cf != $cfs) {
            $mst = Db::connect('G4BOX_SA_BILL_DB')->table('TAccountCashMst')->where('UserNo', $usn)->find();
            if (!$mst) {
                $sql = "EXECUTE WSP_GIVE_CURRENCY @p_USN = ?, @p_GiveUSN = ?, @p_Type = 'C', @p_Ammount = ?, @p_Result = 0";
                Db::connect('G4BOX_SA_BILL_DB')->execute($sql, [$usn,$usn,$cf]);
            } else {
                $oldCashReal = $mst['Cash'];
                if ($cf != $oldCashReal) {
                    $formattedDate = date('Y-m-d H:i:s', time());
                    if ($cf > $cfs){
                        $a = $cf-$cfs;
                        Db::connect('G4BOX_SA_BILL_DB')->table('TAccountCashMst')->where('UserNo', $usn)->update([
                            'Cash' => $cf,
                            'TINCash' => $mst['TINCash']+$a,
                            'UpdDate' => $formattedDate
                        ]);
                    }else{
                        $a = $cfs-$cf;
                        $TOUTCash = $mst['TOUTCash'] + $a;
                        Db::connect('G4BOX_SA_BILL_DB')->table('TAccountCashMst')->where('UserNo', $usn)->update([
                            'CashR' => $cf,
                            'TOUTCash' => $TOUTCash,
                            'UpdDate' => $formattedDate
                        ]);
                    }


                }
            }

            $userinfo = Db::connect('game_db')->table('CF_USER')->where('USN', $usn)->update([
                'NICK' => iconv("ISO-8859-1", "UTF-8", iconv("UTF-8", "GB18030", $nick)),
                'LEV' => $lev,
                'EXP' => $exp,
                'HOLD_TYPE' => $type,
                'GAME_POINT' => $gp
            ]);
            if ($userinfo) {
                AdminLog::insert([
                    'name' => session('admin_name'),
                    'content' => '管理员“' . session('admin_name') . '”修改了用户' . $usn . '的信息',
                    'type' => 3,
                    'ip' => Request::ip(),
                    'create_time' => time()
                ]);
                return json([
                    'code' => 200,
                    'msg' => '更新用户资料成功'
                ]);
            } else {
                return json([
                    'code' => 501,
                    'msg' => '异常出错'
                ]);
            }
        }

        $originalUserInfo = Db::connect('game_db')->table('CF_USER')->where('USN', $usn)->find();
        if (!$originalUserInfo) {
            return json([
                'code' => 502,
                'msg' => '用户不存在'
            ]);
        }
        $userinfo = Db::connect('game_db')->table('CF_USER')->where('USN', $usn)->update([
            'NICK' => iconv("ISO-8859-1", "UTF-8", iconv("UTF-8", "GB18030", $nick)),
            'LEV' => $lev,
            'EXP' => $exp,
            'HOLD_TYPE' => $type,
            'GAME_POINT' => $gp
        ]);

        if ($userinfo) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”修改了用户USN：' . $usn . '的信息',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '更新用户资料成功'
            ]);
        } else {
            return json([
                'code' => 501,
                'msg' => '异常出错'
            ]);
        }
    }

    public function banned()
    {
        if (empty(input('userId')) || empty(input('state'))) {
            return json([
                'code' => 500,
                'msg' => '参数不能为空'
            ]);
        }

        $originalUserInfo = Db::connect('game_db')->table('CF_USER')->where('USN', input('userId'))->find();
        if (!$originalUserInfo) {
            return json([
                'code' => 502,
                'msg' => '用户不存在'
            ]);
        } else {
            Db::connect('game_db')->table('CF_USER')->where('USN', input('userId'))->update([
                'HOLD_TYPE' => input('state')
            ]);
            $text = input('state') == 'A' ? '解封' : '封禁';
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”' . $text . '用户USN：' . $originalUserInfo['USN'] . '',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '修改成功'
            ]);
        }
    }

    public function shopStatus()
    {
        if (empty(input('Id')) || empty(input('state'))) {
            return json([
                'code' => 500,
                'msg' => '参数不能为空'
            ]);
        }

        $status = Shop::where('id', input('Id'))->find();
        if (!$status) {
            return json([
                'code' => 502,
                'msg' => '商品不存在'
            ]);
        } else {
            Shop::where('id', input('Id'))->update([
                'status' => input('state')
            ]);
            $text = input('state') == 1 ? '上架' : '下架';
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”' . $text . '了商品【' . $status['title'] . '】',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '修改成功'
            ]);
        }
    }

    public function SendItemStatus(){
        if (empty(input('Id')) || empty(input('state'))) {
            return json([
                'code' => 500,
                'msg' => '参数不能为空'
            ]);
        }
        SenditemAuth::where('id', input('Id'))->update([
            'status' => input('state')
        ]);
        $text = input('state') == 1 ? '启用' : '禁用';
        return json([
            'code' => 200,
            'msg' => '修改成功'
        ]);
    }

    public function agencyStatus(){
        if (empty(input('Id')) || empty(input('state'))) {
            return json([
                'code' => 500,
                'msg' => '参数不能为空'
            ]);
        }

        $status = AgencyUser::where('id', input('Id'))->find();
        if (!$status) {
            return json([
                'code' => 502,
                'msg' => '用户不存在'
            ]);
        } else {
            AgencyUser::where('id', input('Id'))->update([
                'status' => input('state')
            ]);
            $text = input('state') == 1 ? '启用' : '禁用';
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”' . $text . '了代理账号【' . $status['username'] . '】',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '修改成功'
            ]);
        }
    }

    public function AdminStatus()
    {
        if (empty(input('Id')) || empty(input('state'))) {
            return json([
                'code' => 500,
                'msg' => '参数不能为空'
            ]);
        }

        $status = Admins::where('id', input('Id'))->find();
        if (!$status) {
            return json([
                'code' => 502,
                'msg' => '用户不存在'
            ]);
        } else {
            if (session('admin_id') == input('Id')) {
                return json([
                    'code' => 500,
                    'msg' => '您不能禁用自己的账号'
                ]);
            }
            Admins::where('id', input('Id'))->update([
                'status' => input('state')
            ]);
            $text = input('state') == 1 ? '启用' : '禁用';
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”' . $text . '了【' . $status['username'] . '】',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '修改成功'
            ]);
        }
    }

    public function payUser()
    {
        if (empty(input('usn'))) {
            return json([
                'code' => 201,
                'msg' => 'USN不能为空'
            ]);
        }
        $sql = "EXECUTE WSP_GIVE_CURRENCY @p_USN = ?, @p_GiveUSN = ?, @p_Type = 'C', @p_Ammount = ?, @p_Result = 0";

        Db::connect('CF_SA_WEB_DB')->execute($sql, [input('usn'),input('usn'),input('value')]);
        AdminLog::insert([
            'name' => session('admin_name'),
            'content' => '管理员“' . session('admin_name') . '”给【' . input('usn') . '】充值了CF点：' . input('value'),
            'type' => 3,
            'ip' => Request::ip(),
            'create_time' => time()
        ]);
        return json([
            'code' => 200,
            'msg' => '充值成功'
        ]);
    }

    public function DelCdk()
    {
        $id = input('id');
        $res = Cdks::where('id', $id)->delete();
        if ($res) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了CDK',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '删除成功'
            ]);
        } else {
            return json([
                'code' => 201,
                'msg' => 'id不能为空'
            ]);
        }
    }

    public function DelShop()
    {
        $id = input('id');
        $res = Shop::where('id', $id)->delete();
        if ($res) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了商品',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '商品删除成功'
            ]);
        } else {
            return json([
                'code' => 201,
                'msg' => 'id不能为空'
            ]);
        }
    }

    public function DelSendItem(){
        $id = input('id');
        $res = SenditemAuth::where('id', $id)->delete();
        if ($res) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了用户的物品权限',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '用户删除成功'
            ]);
        } else {
            return json([
                'code' => 201,
                'msg' => 'id不能为空'
            ]);
        }
    }

    public function Delagency(){
        $id = input('id');
        $res = AgencyUser::where('id', $id)->delete();
        if ($res) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了代理用户',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '用户删除成功'
            ]);
        } else {
            return json([
                'code' => 201,
                'msg' => 'id不能为空'
            ]);
        }
    }

    public function DelAdmin()
    {
        $id = input('id');
        $res = Admins::where('id', $id)->delete();
        if ($res) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了用户',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '用户删除成功'
            ]);
        } else {
            return json([
                'code' => 201,
                'msg' => 'id不能为空'
            ]);
        }
    }

    public function DelNews()
    {
        $id = input('id');
        $res = News::where('id', $id)->delete();
        if ($res) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了文章',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '文章删除成功'
            ]);
        } else {
            return json([
                'code' => 201,
                'msg' => 'id不能为空'
            ]);
        }
    }

    public function DelEvents()
    {
        $id = input('id');
        $res = Events::where('id', $id)->delete();
        if ($res) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了文章',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '文章删除成功'
            ]);
        } else {
            return json([
                'code' => 201,
                'msg' => 'id不能为空'
            ]);
        }
    }

    public function DelCdkAll()
    {
        $ids = request()->param('ids');
        if (empty($ids)) {
            return json([
                'code' => 400,
                'msg' => 'ID列表为空'
            ]);
        }
        $ids = explode(",", $ids);
        $deletedCount = Cdks::destroy($ids);
        if ($deletedCount) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了CDK',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '删除成功',
                'deleted_count' => $deletedCount
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '删除失败'
            ]);
        }
    }

    public function DelShopAll()
    {
        $ids = request()->param('ids');
        if (empty($ids)) {
            return json([
                'code' => 400,
                'msg' => 'ID列表为空'
            ]);
        }
        $ids = explode(",", $ids);
        $deletedCount = Shop::destroy($ids);
        if ($deletedCount) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了商品',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '删除成功',
                'deleted_count' => $deletedCount
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '删除失败'
            ]);
        }
    }

    public function DelSendItemAll()
    {
        $ids = request()->param('ids');
        if (empty($ids)) {
            return json([
                'code' => 400,
                'msg' => 'ID列表为空'
            ]);
        }
        $ids = explode(",", $ids);
        $deletedCount = SenditemAuth::destroy($ids);
        if ($deletedCount) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了用户的物品权限',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '删除成功',
                'deleted_count' => $deletedCount
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '删除失败'
            ]);
        }
    }

    public function DelagencyAll(){
        $ids = request()->param('ids');
        if (empty($ids)) {
            return json([
                'code' => 400,
                'msg' => 'ID列表为空'
            ]);
        }
        $ids = explode(",", $ids);
        $deletedCount = AgencyUser::destroy($ids);
        if ($deletedCount) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了代理用户',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '删除成功',
                'deleted_count' => $deletedCount
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '删除失败'
            ]);
        }
    }


    public function DelAdminAll()
    {
        $ids = request()->param('ids');
        if (empty($ids)) {
            return json([
                'code' => 400,
                'msg' => 'ID列表为空'
            ]);
        }
        $ids = explode(",", $ids);
        $deletedCount = Admins::destroy($ids);
        if ($deletedCount) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了用户',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '删除成功',
                'deleted_count' => $deletedCount
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '删除失败'
            ]);
        }
    }

    public function DelEventsAllLog()
    {
        $ids = request()->param('ids');
        if (empty($ids)) {
            return json([
                'code' => 400,
                'msg' => 'ID列表为空'
            ]);
        }
        $ids = explode(",", $ids);
        $deletedCount = EventsLog::destroy($ids);
        if ($deletedCount) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了活动',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '删除成功',
                'deleted_count' => $deletedCount
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '删除失败'
            ]);
        }
    }

    public function DelShopAllLog()
    {
        $ids = request()->param('ids');
        if (empty($ids)) {
            return json([
                'code' => 400,
                'msg' => 'ID列表为空'
            ]);
        }
        $ids = explode(",", $ids);
        $deletedCount = ShopLog::destroy($ids);
        if ($deletedCount) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了商品购买记录',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '删除成功',
                'deleted_count' => $deletedCount
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '删除失败'
            ]);
        }
    }

    public function delInviteLog(){
        $res = InviteLog::where(1)->delete(true);

        if ($res) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了邀请记录',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '删除成功'
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '删除失败'
            ]);
        }
    }

    public function delUserLog(){
        $res = UserLog::where(1)->delete(true);

        if ($res) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了注册记录',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '删除成功'
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '删除失败'
            ]);
        }
    }


    public function delEventsLog()
    {
        $res = EventsLog::where(1)->delete(true);

        if ($res) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了活动领取记录',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '删除成功'
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '删除失败'
            ]);
        }
    }

    public function delShopLog()
    {
        $res = ShopLog::where(1)->delete(true);

        if ($res) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了商品购买记录',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '删除成功'
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '删除失败'
            ]);
        }
    }

    public function DelagencycodeAll(){
        $ids = request()->param('ids');
        if (empty($ids)) {
            return json([
                'code' => 400,
                'msg' => 'ID列表为空'
            ]);
        }
        $ids = explode(",", $ids);
        $deletedCount = AgencyCode::destroy($ids);
        if ($deletedCount) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了代理注册邀请码',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '删除成功',
                'deleted_count' => $deletedCount
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '删除失败'
            ]);
        }
    }

    public function DelInvitationAll()
    {
        $ids = request()->param('ids');
        if (empty($ids)) {
            return json([
                'code' => 400,
                'msg' => 'ID列表为空'
            ]);
        }
        $ids = explode(",", $ids);
        $deletedCount = Invitation::destroy($ids);
        if ($deletedCount) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了注册邀请码',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '删除成功',
                'deleted_count' => $deletedCount
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '删除失败'
            ]);
        }
    }

    public function DelInvitation()
    {
        $ids = request()->param('id');
        if (empty($ids)) {
            return json([
                'code' => 400,
                'msg' => 'ID列表为空'
            ]);
        }
        $ids = explode(",", $ids);
        $deletedCount = Invitation::destroy($ids);
        if ($deletedCount) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了注册邀请码',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '删除成功',
                'deleted_count' => $deletedCount
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '删除失败'
            ]);
        }
    }

    public function DelNewsAll()
    {
        $ids = request()->param('ids');
        if (empty($ids)) {
            return json([
                'code' => 400,
                'msg' => 'ID列表为空'
            ]);
        }
        $ids = explode(",", $ids);
        $deletedCount = News::destroy($ids);
        if ($deletedCount) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了文章',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '删除成功',
                'deleted_count' => $deletedCount
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '删除失败'
            ]);
        }
    }

    public function DelEventsAll()
    {
        $ids = request()->param('ids');
        if (empty($ids)) {
            return json([
                'code' => 400,
                'msg' => 'ID列表为空'
            ]);
        }
        $ids = explode(",", $ids);
        $deletedCount = Events::destroy($ids);
        if ($deletedCount) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”删除了活动',
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '删除成功',
                'deleted_count' => $deletedCount
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '删除失败'
            ]);
        }
    }

    public function BanUserUsn()
    {
        $Config = Configs::gets();
        Db::connect('game_db')->table('CF_USER')->where('USN', input('id'))->update([
            'HOLD_TYPE' => 'E'
        ]);
        $ip = Db::connect('game_db')->table('CF_MEMBER')->where('USN', input('id'))->find();
        $ip = long2ip($ip['USER_IP']);

        $ip = implode('.', array_reverse(explode('.', $ip)));


        curl($Config['url'] . '/?token=' . $Config['token'] . '&type=ban&ip=' . $ip);

        AdminLog::insert([
            'name' => session('admin_name'),
            'content' => '管理员“' . session('admin_name') . '”封禁了用户角色名：' . input('id'),
            'type' => 3,
            'ip' => Request::ip(),
            'create_time' => time()
        ]);
        return json([
            'code' => 200,
            'msg' => '封禁成功'
        ]);
    }


    public function OnlineUser()
    {
        $Config = Configs::gets();
        $userIps = Db::connect('game_db')->table('CF_USER_AUTH')->field('USER_IP')->group('USER_IP')->select();
        $standardIps = [];
        foreach ($userIps as $item) {
            $intIp = $item['USER_IP'];
            if (empty($intIp) || !is_numeric($intIp) || !filter_var($intIp, FILTER_VALIDATE_INT)) {
                continue;
            }
            $ipParts = [];
            for ($i = 3; $i >= 0; $i--) {
                $ipParts[] = ($intIp >> ($i * 8)) & 0xFF;
            }
            $standardIp = implode('.', $ipParts);

            $standardIps[] = reverseIp($standardIp);
        }

        $remoteData = curls($Config['url'] . '/?type=ip&token=' . $Config['token']);
        $remoteData = json_decode($remoteData, true);
        $remoteIps = $remoteData['ip'];
        $matchedIps = array_intersect($remoteIps, $standardIps);
        $unsignedInts = [];
        $signedInts = [];

        foreach ($matchedIps as $ip) {
            $reversedIp = reverseIp($ip);
            $unsignedInt = ipToUnsignedInt($reversedIp);
            $signedInt = ($unsignedInt > 0x7FFFFFFF) ? $unsignedInt - 0x100000000 : $unsignedInt;

            $unsignedInts[] = $unsignedInt;
            $signedInts[] = $signedInt;
        }

        $macs = Db::connect('game_db')->table('CF_MEMBER')->whereIn('USER_IP', $signedInts)->select();
        $result = [];
        foreach ($macs as $mac) {
            $usn = $mac['USN'];
            $User = Db::connect('game_db')->table('CF_USER')->where('USN', $usn)->field('USN,NICK,LAST_PLAY_DATE')->find();
            if (isset($User)) {
                $mac['NICK'] = $User['NICK'];
                $mac['LAST_PLAY_DATE'] = $User['LAST_PLAY_DATE'];
                $result[] = $mac;
            }
        }
        return json([
            'code' => 0,
            'count' => count($result),
            'data' => $result
        ]);


    }

    public function avc()
    {
        $text = "啊哈哈";
        $hexString = '';

// 假设文本已经是UTF-8编码（对于这三个字符，它与GB2312可能相同）
        for ($i = 0; $i < mb_strlen($text, 'UTF-8'); $i++) {
            $byte = mb_substr($text, $i, 1, 'UTF-8');
            $hex = bin2hex($byte);
            // 如果字节的十六进制表示只有一位，前面补零
            if (strlen($hex) == 1) {
                $hex = '0' . $hex;
            }
            $hexString .= '\\x' . $hex;
        }

        echo "GB2312 (hex with \\x prefix): " . $hexString . PHP_EOL;
        $iso88591String = '';
        $hexParts = explode('\\x', $hexString);
        array_shift($hexParts); // 移除第一个空字符串（由于字符串以'\x'开头）

        foreach ($hexParts as $hex) {
            $byte = chr(hexdec($hex));
            $iso88591String .= $byte;
        }

        echo "Attempted ISO-8859-1 decoding (will not be meaningful): " . $iso88591String . PHP_EOL;

    }

    public function test()
    {
        $deleteConfigs = [
            ['db' => 'game_db', 'table' => 'CF_MEMBER', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_USER', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_USER_CHARACTER', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_USER_INFO', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_USER_INVENTORY', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_USER_KEY_SETTING', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_USER_NEWBIEMISSION_ACHIEVE', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_USER_SACK', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_USER_STATUS', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_WISH_ITEM', 'field' => 'USN'],
            ['db' => 'guild_db', 'table' => 'CF_CLAN_CHAT_OPTION', 'field' => 'MemberUsn'],
            ['db' => 'guild_db', 'table' => 'CF_CLAN_CREATE_COUNT', 'field' => 'USN'],
            ['db' => 'guild_db', 'table' => 'CF_CLAN_INFO', 'field' => 'MasterUsn'],
            ['db' => 'guild_db', 'table' => 'CF_CLAN_MEMBER_INFO', 'field' => 'MemberUsn'],
            ['db' => 'guild_db', 'table' => 'CF_CLAN_MEMBER_STAT_INFO', 'field' => 'USN'],
            ['db' => 'G4BOX_SA_BILL_DB', 'table' => 'TAccountMst', 'field' => 'UserNo'],
            ['db' => 'G4BOX_SA_BILL_DB', 'table' => 'TCashMst', 'field' => 'CashNo'],
            ['db' => 'G4BOX_SA_BILL_DB', 'table' => 'TCashUseDetail', 'field' => 'CashNo'],
            ['db' => 'G4BOX_SA_BILL_DB', 'table' => 'TItemHoldMst', 'field' => 'UserNo'],
            ['db' => 'G4BOX_SA_BILL_DB', 'table' => 'TPurchaseMst', 'field' => 'UserNo'],
            ['db' => 'G4BOX_SA_BILL_DB', 'table' => 'TSequence', 'field' => 'SeqNo'],
        ];
        foreach ($deleteConfigs as $config) {
            $dbName = $config['db'];
            $tableName = $config['table'];
            $res = Db::connect($dbName)->execute('TRUNCATE TABLE ' . $tableName);

        }
        dump($res);
    }

    public function DelUser()
    {
        return json([
            'code' => 507,
            'msg' => '还没有写呢'
        ]);
        die;
        $id = input('id');
        if (empty($id)) {
            return json([
                'code' => 501,
                'msg' => 'ID不能为空'
            ]);
        }
        $deleteConfigs = [
            ['db' => 'game_db', 'table' => 'CF_MEMBER', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_USER', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_USER_CHARACTER', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_USER_INFO', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_USER_INVENTORY', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_USER_KEY_SETTING', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_USER_NEWBIEMISSION_ACHIEVE', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_USER_SACK', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_USER_STATUS', 'field' => 'USN'],
            ['db' => 'game_db', 'table' => 'CF_WISH_ITEM', 'field' => 'USN'],
            ['db' => 'guild_db', 'table' => 'CF_CLAN_CHAT_OPTION', 'field' => 'MemberUsn'],
            ['db' => 'guild_db', 'table' => 'CF_CLAN_CREATE_COUNT', 'field' => 'USN'],
            ['db' => 'guild_db', 'table' => 'CF_CLAN_INFO', 'field' => 'MasterUsn'],
            ['db' => 'guild_db', 'table' => 'CF_CLAN_MEMBER_INFO', 'field' => 'MemberUsn'],
            ['db' => 'guild_db', 'table' => 'CF_CLAN_MEMBER_STAT_INFO', 'field' => 'USN'],
            ['db' => 'G4BOX_SA_BILL_DB', 'table' => 'TAccountMst', 'field' => 'UserNo'],
            ['db' => 'G4BOX_SA_BILL_DB', 'table' => 'TCashMst', 'field' => 'CashNo'],
            ['db' => 'G4BOX_SA_BILL_DB', 'table' => 'TCashUseDetail', 'field' => 'CashNo'],
            ['db' => 'G4BOX_SA_BILL_DB', 'table' => 'TItemHoldMst', 'field' => 'UserNo'],
            ['db' => 'G4BOX_SA_BILL_DB', 'table' => 'TPurchaseMst', 'field' => 'UserNo'],
            ['db' => 'G4BOX_SA_BILL_DB', 'table' => 'TSequence', 'field' => 'SeqNo'],
        ];
        $successMessages = [];
        $errorMessages = [];
        foreach ($deleteConfigs as $config) {
            try {
                $result = Db::connect($config['db'])->table($config['table'])->where($config['field'], $id)->delete();
                if ($result) {
                    AdminLog::insert([
                        'name' => session('admin_name'),
                        'content' => '管理员“' . session('admin_name') . '”删除了用户USN：' . $id,
                        'type' => 3,
                        'ip' => Request::ip(),
                        'create_time' => time()
                    ]);
                    $successMessages[] = "表 {$config['table']} 删除成功";
                } else {
                    //continue;
                }

            } catch (DbException $e) {
                $errorMessages[] = "删除表 {$config['table']} 失败：" . $e->getMessage();
            } catch (\Exception $e) {
                $errorMessages[] = "删除表 {$config['table']} 时发生未知错误：" . $e->getMessage();
            }
        }
        if (!empty($errorMessages)) {
            return json(['code' => '500', 'msg' => implode('；', $errorMessages)]);
        } else {
            return json(['code' => '200', 'msg' => implode('；', $successMessages)]);
        }
    }

    public function AddUser()
    {
        $username = input('username');
        $password = input('password');
        $email = input('email');
        $ip = request()->ip();
        if (empty($username) || empty($password) || empty($email)) {
            return json([
                'code' => 201,
                'msg' => '账号密码不能为空'
            ]);
        }
        $userLog = UserLog::where('email', $email)->find();
        if ($userLog) {
            return json([
                'code' => 203,
                'msg' => '邮箱已存在'
            ]);
        }
        $db = Db::connect('game_db');
        $result = $db->table('CF_MEMBER')->where('USER_ID', $username)
            ->find();
        if ($result) {
            return json([
                'code' => 202,
                'msg' => '账号已存在'
            ]);
        }
//        $db->table('CF_MEMBER')->insert([
//            'USER_ID' => input('username'),
//            'LUSER_ID' => strtolower(input('username')),
//            'USER_PASS' => md5(input('password').$siteConfig['md5pass']),
//            'EMAIL' => input('email'),
//            'ISACTIVE' => 1,
//            'ISPROMOUSER' => 0,
//            'NEEDVALIDATION' => 0,
//            'REG_DATE' => date("Y-m-d H:i:s").'.'.mt_rand(100,999),
//            'ISPROMOUSER' => null,
//
//        ]);

        $sql = "EXECUTE PROC_WEB_USER_INFO_INS @p_User = ?, @p_User_pass = ? , @p_Mail = ? ,@p_Result=0";
        Db::connect('game_db')->execute($sql, [$username, $password,$email]);


        AdminLog::insert([
            'name' => session('admin_name'),
            'content' => '管理员“' . session('admin_name') . '”注册了账号：' . $username,
            'type' => 3,
            'ip' => Request::ip(),
            'create_time' => time()
        ]);

        $userLog = new UserLog();
        $userLog->userid = $username;
        $userLog->email = $email;
        $userLog->create_time = time();
        $userLog->ip = $ip;
        $userLog->Invite = 0;
        $userLog->type = 3;
        $userLog->save();
        return json([
            'code' => 200,
            'msg' => '注册成功'
        ]);
    }

    public function retPass()
    {

        $NewPassword = input('post.password');
        $usn = input('post.usn');

        if (empty($NewPassword)) {
            return json([
                'code' => 201,
                'msg' => '密码不能为空'
            ]);
        }

        $res = Db::connect('game_db')->table('CF_MEMBER')->where('USN', $usn)->update([
            'USER_PASS' => Db::raw("HASHBYTES('SHA1', '{$NewPassword}')"),
        ]);




        if ($res || $res = 1) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”重置了USN：' . $usn . '的密码为：' . $NewPassword,
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '密码修改成功'
            ]);
        } else {
            return json([
                'code' => 202,
                'msg' => '密码修改失败'
            ]);
        }
    }

    public function addEvents()
    {
        $res = new Events();
        $res->title = input('title');
        $res->img = input('img');
        $res->content = input('content');
        $res->value = input('value');
        $res->url = input('url');
        $res->type = input('type');
        $res->without = input('without');
        $res->start_time = strtotime(input('start_time'));
        $res->end_time = strtotime(input('end_time'));
        $res->create_time = time();
        $res->save();
        AdminLog::insert([
            'name' => session('admin_name'),
            'content' => '管理员“' . session('admin_name') . '”添加了活动' . input('title'),
            'type' => 3,
            'ip' => Request::ip(),
            'create_time' => time()
        ]);
        return json([
            'code' => 200,
            'msg' => '活动添加成功'
        ]);
    }

    public function editEvents()
    {
        $Id = input('id');
        if (empty($Id)) {
            return json([
                'code' => 400,
                'msg' => '新闻ID不能为空'
            ]);
        }
        $res = Events::class;
        $res = $res::where('id', $Id)->find();
        if (!$res) {
            return json([
                'code' => 404,
                'msg' => '新闻不存在'
            ]);
        }

        $res->title = input('title');
        $res->img = input('img');
        $res->content = input('content');
        $res->value = input('value');
        $res->url = input('url');
        $res->type = input('type');
        $res->without = input('without');
        $res->start_time = strtotime(input('start_time'));
        $res->end_time = strtotime(input('end_time'));
        $res->create_time = time();
        $res->save();
        if ($res) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”修改了活动' . input('title'),
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '活动更新成功'
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '活动更新时出错'
            ]);
        }

    }

    public function AddShop()
    {
        if (empty(input('itemid')) || empty(input('title')) || empty(input('img')) || empty(input('type')) || empty(input('money')) || empty(input('itemid'))) {
            return json([
                'code' => 201,
                'msg' => '所有输入框都与选项都不能为空'
            ]);
        }
        $shop = new Shop();
        $shop->title = input('title');
        $shop->money = input('money');
        $shop->type = input('type');
        $shop->itemid = input('itemid');
        $shop->img = input('img');
        $shop->status = 1;
        $shop->create_time = time();
        $shop->save();
        AdminLog::insert([
            'name' => session('admin_name'),
            'content' => '管理员“' . session('admin_name') . '”添加了商品' . input('title'),
            'type' => 3,
            'ip' => Request::ip(),
            'create_time' => time()
        ]);
        return json([
            'code' => 200,
            'msg' => '商品添加成功'
        ]);
    }

    public function AddSendItem(){

        //检测是否已经注册
        $user = Db::connect('game_db')->table('CF_MEMBER')->where('USER_ID',input('username'))->find();
        if (!$user){
            return json([
                'code'  =>  201,
                'msg'   =>  '检查到该账号不存在游戏数据库中'
            ]);
        }


        $type = SenditemAuth::where('userid', input('username'))->find();
        if ($type) {
            return json([
                'code' => 500,
                'msg' => '账号已存在'
            ]);
        }
        $res = new SenditemAuth();
        $res->usn = $user['USN'];
        $res->userid = input('username');
        $res->status = input('status');
        $res->create_time = time();
        $res->save();
        return json([
            'code' => 200,
            'msg' => '用户添加成功'
        ]);
    }

    public function Addagency(){
        $user = Admins::where('id', session('admin_id'))->find();
        if ($user['rank'] != 1) {
            return json([
                'code' => 403,
                'msg' => '你没有足够的权限修改管理员信息'
            ]);
        }

        if (empty(input('password'))){
            return json([
                'code' => 405,
                'msg' => '密码不能为空'
            ]);
        }

        // 检查用户名是否已存在
        $adminExistsByUsername = AgencyUser::where('user', input('user'))->find();
        if ($adminExistsByUsername) {
            return json([
                'code' => 500,
                'msg' => '用户名已存在'
            ]);
        }
        $admin = new AgencyUser();
        $admin->user = input('user');
        $admin->pass = password_hash(input('password'), PASSWORD_BCRYPT);
        $admin->status = input('status');
        $admin->cf = input('cf');
        $admin->money = empty(input('money')) ? 0 :input('money');
        $admin->create_time = time();
        $admin->save();
        return json([
            'code' => 200,
            'msg' => '用户添加成功'
        ]);
    }

    public function AddAdmin()
    {
        $user = Admins::where('id', session('admin_id'))->find();
        if ($user['rank'] != 1) {
            return json([
                'code' => 403,
                'msg' => '你没有足够的权限修改管理员信息'
            ]);
        }

        $adminExistsByEmail = Admins::where('email', input('email'))->find();
        if ($adminExistsByEmail) {
            return json([
                'code' => 500,
                'msg' => '邮箱已存在'
            ]);
        }

        // 检查用户名是否已存在
        $adminExistsByUsername = Admins::where('username', input('username'))->find();
        if ($adminExistsByUsername) {
            return json([
                'code' => 500,
                'msg' => '用户名已存在'
            ]);
        }
        $admin = new Admins();
        $admin->email = input('email');
        $admin->username = input('username');
        $admin->token = getToken(32);
        $admin->password = password_hash(input('pass'), PASSWORD_BCRYPT);
        $admin->status = input('status');
        $admin->rank = 0;
        $admin->create_time = time();
        $admin->save();
        return json([
            'code' => 200,
            'msg' => '用户添加成功'
        ]);
    }

    public function addNews()
    {
        if (empty(input('title')) || empty(input('content'))) {
            return json([
                'code' => 201,
                'msg' => '所有输入框都与选项都不能为空'
            ]);
        }
        $news = new News();
        $news->title = input('title');
        $news->content = input('content');
        $news->create_time = time();
        $news->save();
        AdminLog::insert([
            'name' => session('admin_name'),
            'content' => '管理员“' . session('admin_name') . '”添加了文章' . input('title'),
            'type' => 3,
            'ip' => Request::ip(),
            'create_time' => time()
        ]);
        return json([
            'code' => 200,
            'msg' => '文章添加成功'
        ]);
    }

    public function editNews()
    {
        $newsId = input('id');
        if (empty($newsId)) {
            return json([
                'code' => 400,
                'msg' => '新闻ID不能为空'
            ]);
        }

        $title = input('title');
        $content = input('content');
        if (empty($title) || empty($content)) {
            return json([
                'code' => 201,
                'msg' => '标题和内容都不能为空'
            ]);
        }
        $newsModel = News::class;

        // 使用模型方法检查新闻是否存在
        $news = $newsModel::where('id', $newsId)->find();
        if (!$news) {
            return json([
                'code' => 404,
                'msg' => '新闻不存在'
            ]);
        }

        $updateData = [
            'title' => $title,
            'content' => $content,
        ];
        $result = $news->save($updateData);
        if ($result) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”修改了文章' . input('title'),
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '文章更新成功'
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '更新文章时出错'
            ]);
        }
    }

    public function uploadImage()
    {
        // 获取上传的文件

        $file = request()->file('file');
        $validate = new Validate();
        // 设置验证规则
        $validate->rule('file', 'require|image|max:5242880'); // require表示必传，image表示图片格式，max表示最大5MB

        // 验证文件
        $savename = \think\facade\Filesystem::disk('public')->putFile('topic', $file);
        if ($savename) {
            $url = config('app.url') . '/storage/' . $savename;

            return json(['location' => $url]);
        }

    }

    public function Editagency(){
        $request = Request::instance();
        $id = $request->param('id', 0);
        $username = $request->param('user');
        $password = $request->param('password');
        $status = $request->param('status');
        $money = $request->param('money');
        $cf = $request->param('cf');
        $admin = Admins::find(session('admin_id'));
        $originalAdmin = AgencyUser::find($id);
        if (!$originalAdmin) {
            return json([
                'code' => 500,
                'msg' => '代理不存在'
            ]);
        }
        if ($admin['rank'] != 1) {
            return json([
                'code' => 403,
                'msg' => '你没有足够的权限修改管理员信息'
            ]);
        }
        $updateData = [
            'status' => $status,
        ];
        if ($username != $originalAdmin['user']) {
            $existUsername = AgencyUser::where('user', $username)->find();
            if ($existUsername) {
                return json([
                    'code' => 500,
                    'msg' => '用户名已存在'
                ]);
            }
            $updateData['user'] = $username;
        }

        if ($password) {
            $updateData['pass'] = password_hash($password, PASSWORD_DEFAULT);
        }
        if ($money){
            $updateData['money'] = $money;
        }
        if ($cf){
            $updateData['cf'] = $cf;
        }
        $res = AgencyUser::where('id', $id)->update($updateData);

        if ($res) {
            return json([
                'code' => 200,
                'msg' => '用户修改成功'
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '用户修改失败'
            ]);
        }
    }


    public function EditAdmin()
    {
        $request = Request::instance();
        $id = $request->param('id', 0);
        $email = $request->param('email');
        $username = $request->param('username');
        $password = $request->param('pass');
        $status = $request->param('status');

        $originalAdmin = Admins::find($id);
        if (!$originalAdmin) {
            return json([
                'code' => 500,
                'msg' => '管理员不存在'
            ]);
        }

        if ($originalAdmin['rank'] != 1) {
            return json([
                'code' => 403,
                'msg' => '你没有足够的权限修改管理员信息'
            ]);
        }
        $updateData = [
            'status' => $status,
        ];

        if ($email != $originalAdmin['email']) {
            $existEmail = Admins::where('email', $email)->find();
            if ($existEmail) {
                return json([
                    'code' => 500,
                    'msg' => '邮箱已存在'
                ]);
            }
            $updateData['email'] = $email;
        }
        if ($username != $originalAdmin['username']) {
            $existUsername = Admins::where('username', $username)->find();
            if ($existUsername) {
                return json([
                    'code' => 500,
                    'msg' => '用户名已存在'
                ]);
            }
            $updateData['username'] = $username;
        }

        if ($password) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        $res = Admins::where('id', $id)->update($updateData);

        if ($res) {
            return json([
                'code' => 200,
                'msg' => '用户修改成功'
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '用户修改失败'
            ]);
        }
    }

    public function EditShop()
    {
        $id = input('id');
        $itemid = input('itemid');
        $title = input('title');
        $img = input('img');
        $type = input('type');
        $money = input('money');
        if (empty($id) || empty($itemid) || empty($title) || empty($img) || empty($type) || empty($money)) {
            return json([
                'code' => 400,
                'msg' => '所有输入框都不能为空'
            ]);
        }
        $data = [
            'title' => $title,
            'itemid' => $itemid,
            'money' => $money,
            'type' => $type,
            'img' => $img,
        ];
        $res = Shop::where('id', $id)->update($data);
        if ($res) {
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”修改了商品' . input('title'),
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '商品修改成功'
            ]);
        } else {
            return json([
                'code' => 500,
                'msg' => '商品修改失败'
            ]);
        }
    }

    public function ShopList()
    {
        $page = input('page', 1);
        $limit = input('limit', 10);
        $keyword = input('Name'); // 获取搜索关键字

        // 初始化查询构建器
        $query = Shop::order('create_time', 'DESC');

        // 如果存在关键字，则添加模糊搜索条件
        if (!empty($keyword)) {
            $query->where('title', 'like', '%' . $keyword . '%');
        }

        // 分页查询数据
        $result = $query->page($page, $limit)->select();
        $countQuery = clone $query;
        $count = $countQuery->count();

        return json([
            'code' => 0,
            'count' => $count,
            'data' => $result
        ]);
    }

    public function ReportList()
    {
        $page = input('page', 1);
        $limit = input('limit', 10);
        $keyword = input('Name'); // 获取搜索关键字

        // 初始化查询构建器
        $query = Report::order('create_time', 'DESC');

        // 如果存在关键字，则添加模糊搜索条件
        if (!empty($keyword)) {
            $query->where('title', 'like', '%' . $keyword . '%');
        }

        // 分页查询数据
        $result = $query->page($page, $limit)->select();
        $countQuery = clone $query;
        $count = $countQuery->count();

        $json = [];
        foreach ($result as $k => $v) {
            $admin = Admins::where('id', $v['aid'])->find();
            $nickname = empty($admin['username']) ? 0 : $admin['username'];
            switch ($v['status']) {
                case 0:
                    $result = '未审核';
                    break;
                case 1:
                    $result = '已审核';
                    break;
                case 2:
                    $result = '已驳回';
                    break;
                default:
                    $result = '未审核';
            }
            $json[$k] = [
                'id' => $v['id'],
                'usn' => $v['usn'],
                'reportedNickname' => $v['reportedNickname'],
                'appealAccount' => $v['appealAccount'],
                'reportType' => $v['reportType'],
                'content' => $v['content'],
                'status' => $v['status'],
                'aid' => $nickname,
                'result' => $result,
                'update_time' => $v['update_time'],
                'create_time' => $v['create_time']
            ];
        }

        return json([
            'code' => 0,
            'count' => $count,
            'data' => $json
        ]);
    }

    public function agencycodeList(){
        $page = input('page', 1);
        $limit = input('limit', 10);
        $keyword = input('key'); // 获取搜索关键字

        // 初始化查询构建器
        $query = AgencyCode::order('create_time', 'DESC');

        // 如果存在关键字，则添加模糊搜索条件
        if (!empty($keyword)) {
            $query->where('code', 'like', '%' . $keyword . '%');
        }

        // 分页查询数据
        $result = $query->page($page, $limit)->select();
        $countQuery = clone $query;
        $count = $countQuery->count();

        $json = [];
        foreach ($result as $k=>$v){
            $AG = AgencyUser::where('id',$v['uid'])->find();

            if ($v['uid'] == 0){
                $nick = '管理员';
            }else{
                $nick = $AG['user'];
            }

            $json[$k] = [
                'id'    =>  $v['id'],
                'nick'    =>  $nick,
                'username'    =>  $v['username'],
                'code'    =>  $v['code'],
                'status'    =>  $v['status'],
                'create_time'    =>  $v['create_time'],
            ];
        }
        return json([
            'code' => 0,
            'count' => $count,
            'data' => $json
        ]);
    }

    public function InvitationList()
    {
        $page = input('page', 1);
        $limit = input('limit', 10);
        $keyword = input('key'); // 获取搜索关键字

        // 初始化查询构建器
        $query = Invitation::order('create_time', 'DESC');

        // 如果存在关键字，则添加模糊搜索条件
        if (!empty($keyword)) {
            $query->where('code', 'like', '%' . $keyword . '%');
        }

        // 分页查询数据
        $result = $query->page($page, $limit)->select();
        $countQuery = clone $query;
        $count = $countQuery->count();

        return json([
            'code' => 0,
            'count' => $count,
            'data' => $result
        ]);
    }

    public function newsList()
    {
        $page = input('page', 1);
        $limit = input('limit', 10);
        $keyword = input('Name'); // 获取搜索关键字

        // 初始化查询构建器
        $query = News::order('create_time', 'DESC');

        // 如果存在关键字，则添加模糊搜索条件
        if (!empty($keyword)) {
            $query->where('title', 'like', '%' . $keyword . '%');
        }

        // 分页查询数据
        $result = $query->page($page, $limit)->select();
        $countQuery = clone $query;
        $count = $countQuery->count();

        return json([
            'code' => 0,
            'count' => $count,
            'data' => $result
        ]);
    }

    public function SendItemList(){
        $page = input('page', 1);
        $limit = input('limit', 10);
        $keyword = input('Name'); // 获取搜索关键字

        // 初始化查询构建器
        $query = SenditemAuth::order('create_time', 'DESC');

        // 如果存在关键字，则添加模糊搜索条件
        if (!empty($keyword)) {
            $query->where('userid', 'like', '%' . $keyword . '%');
        }

        // 分页查询数据
        $result = $query->page($page, $limit)->select();
        $countQuery = clone $query;
        $count = $countQuery->count();

        return json([
            'code' => 0,
            'count' => $count,
            'data' => $result
        ]);
    }
    public function agencyList(){
        $page = input('page', 1);
        $limit = input('limit', 10);
        $keyword = input('Name'); // 获取搜索关键字

        // 初始化查询构建器
        $query = AgencyUser::order('create_time', 'DESC');

        // 如果存在关键字，则添加模糊搜索条件
        if (!empty($keyword)) {
            $query->where('user', 'like', '%' . $keyword . '%');
        }

        // 分页查询数据
        $result = $query->page($page, $limit)->select();
        $countQuery = clone $query;
        $count = $countQuery->count();

        return json([
            'code' => 0,
            'count' => $count,
            'data' => $result
        ]);
    }

    public function AdminList()
    {
        $page = input('page', 1);
        $limit = input('limit', 10);
        $keyword = input('Name'); // 获取搜索关键字

        // 初始化查询构建器
        $query = Admins::order('create_time', 'DESC');

        // 如果存在关键字，则添加模糊搜索条件
        if (!empty($keyword)) {
            $query->where('username', 'like', '%' . $keyword . '%');
        }

        // 分页查询数据
        $result = $query->page($page, $limit)->select();
        $countQuery = clone $query;
        $count = $countQuery->count();

        return json([
            'code' => 0,
            'count' => $count,
            'data' => $result
        ]);
    }

    public function shoplogLogList()
    {
        $page = input('page', 1);
        $limit = input('limit', 10);
        $keyword = input('username'); // 获取搜索关键字

        // 初始化查询构建器
        $query = ShopLog::order('create_time', 'DESC');

        // 如果存在关键字，则添加模糊搜索条件
        if (!empty($keyword)) {
            $query->where('title', 'like', '%' . $keyword . '%');
        }

        // 分页查询数据
        $result = $query->page($page, $limit)->select();
        $countQuery = clone $query;
        $count = $countQuery->count();

        foreach ($result as $v) {
            $e = Shop::where('id', $v['sid'])->find();
            $v['title'] = $e['title'];
        }

        return json([
            'code' => 0,
            'count' => $count,
            'data' => $result
        ]);
    }

    public function eventsLogList()
    {
        $page = input('page', 1);
        $limit = input('limit', 10);
        $keyword = input('username'); // 获取搜索关键字

        // 初始化查询构建器
        $query = EventsLog::order('create_time', 'DESC');

        // 如果存在关键字，则添加模糊搜索条件
        if (!empty($keyword)) {
            $query->where('title', 'like', '%' . $keyword . '%');
        }

        // 分页查询数据
        $result = $query->page($page, $limit)->select();
        $countQuery = clone $query;
        $count = $countQuery->count();

        foreach ($result as $v) {
            $e = Events::where('id', $v['eid'])->find();
            $v['title'] = $e['title'];
        }

        return json([
            'code' => 0,
            'count' => $count,
            'data' => $result
        ]);
    }

    public function eventsList()
    {
        $page = input('page', 1);
        $limit = input('limit', 10);
        $keyword = input('Name'); // 获取搜索关键字

        // 初始化查询构建器
        $query = Events::order('create_time', 'DESC');

        // 如果存在关键字，则添加模糊搜索条件
        if (!empty($keyword)) {
            $query->where('title', 'like', '%' . $keyword . '%');
        }

        // 分页查询数据
        $result = $query->page($page, $limit)->select();
        $countQuery = clone $query;
        $count = $countQuery->count();

        return json([
            'code' => 0,
            'count' => $count,
            'data' => $result
        ]);
    }

    public function RecordList()
    {
        $page = input('page', 1);
        $limit = input('limit', 10);
        $keyword = input('account'); // 获取搜索关键字

        // 初始化查询构建器
        $query = AdminLog::order('create_time', 'DESC');

        // 如果存在关键字，则添加模糊搜索条件
        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        // 分页查询数据
        $result = $query->page($page, $limit)->select();
        $countQuery = clone $query;
        $count = $countQuery->count();

        return json([
            'code' => 0,
            'count' => $count,
            'data' => $result
        ]);
    }

    public function ItemList()
    {
        $page = (int)input('page', '1', 'trim');
        $limit = (int)input('limit', '10', 'trim');
        $key = input('keyword');
        $query = Db::connect('game_db')->table('CF_ITEM_INFO')
            ->field('ITEM_ID, ITEM_CODE, NAME, ITEM_INDEX, ITEM_TYPE, SHORT_DESCR, SHORT_NAME, ITEM_CATEGORY1, ITEM_CATEGORY2, SALE_TYPE, USE_TYPE1, USE_TYPE2, USE_TYPE3, USE_TYPE5')
            ->limit(($page - 1) * $limit, $limit);

        if (!empty($key)) {
            $query->where('NAME', 'like', '%' . $key . '%'); // 添加模糊搜索条件
        }


        $result = $query->page($page, $limit)->select();
        $countQuery = clone $query;
        $count = $countQuery->count();


        $json = [];
        foreach ($result as $k => $v) {
            $name = empty($China_name['name']) ? '' : $China_name['name'];
            $json[$k] = [
                'ITEM_ID' => $v['ITEM_ID'],
                'NAME' => $v['NAME'],
                'ITEM_CODE' => $v['ITEM_CODE'],
                'ITEM_TYPE' => cfItemType($v['ITEM_TYPE']),
            ];
        }
        return json([
            'code' => 0,
            'count' => $count,
            'data' => $json
        ]);
    }

    public function SendUserItem()
    {
        //用户管理发送物品
        $sql = "EXECUTE WSP_GIVE_ITEM @p_USN = ?, @p_GiveUSN = ?, @p_ID = ?, @p_Name = '', @p_Result = 0";
        Db::connect('CF_SA_WEB_DB')->execute($sql, [input('usn'), input('usn'), input('itemId')]);
        return json([
            'code' => 200,
            'msg' => '发送成功'
        ]);
    }

    public function SendItemAjax()
    {

        $name = input('username');
        $num = input('num');
        $userinfo = Db::connect('game_db')->table('CF_MEMBER')
            ->where('USER_ID', $name)
            ->field('USN')
            ->find();

        if (!$userinfo) {
            return json([
                'code' => 500,
                'msg' => '账号不存在，请检查账号是否正确'
            ]);
        } else {
            $successCount = 0;
            $sql = "EXECUTE WSP_GIVE_ITEM @p_USN = ?, @p_GiveUSN = ?, @p_ID = ?, @p_Name = '', @p_Result = 0";
            for ($i = 0; $i < $num; $i++) {
                $result = Db::connect('CF_SA_WEB_DB')->execute($sql, [$userinfo['USN'], $userinfo['USN'], input('ITEM_ID')]);

                if (!$result) {
                    $successCount++;
                }
            }
            AdminLog::insert([
                'name' => session('admin_name'),
                'content' => '管理员“' . session('admin_name') . '”发送了' . $num . '个物品给USN：' . $name . '，物品代码：' . input('ITEM_ID'),
                'type' => 3,
                'ip' => Request::ip(),
                'create_time' => time()
            ]);
            return json([
                'code' => 200,
                'msg' => '发送成功'
            ]);
        }

    }

    public function ItemListAjax()
    {
        $page = (int)input('page', '1', 'trim');
        $limit = (int)input('limit', '10', 'trim');
        $type = input('type');
        $key = input('key');
        $arg = input('arg');

        // 验证输入参数
        if ($page < 1 || $limit < 1) {
            return json(['code' => 1, 'msg' => 'Invalid page or limit']);
        }
        $query = Db::connect('game_db')->table('CF_ITEM_INFO')
            ->field('ITEM_ID, ITEM_CODE, NAME, ITEM_INDEX, ITEM_TYPE, SHORT_DESCR, SHORT_NAME, ITEM_CATEGORY1, ITEM_CATEGORY2, SALE_TYPE, USE_TYPE1, USE_TYPE2, USE_TYPE3, USE_TYPE5')
            ->limit(($page - 1) * $limit, $limit);
        // 添加模糊搜索条件
        if (!empty($key)) {
            $query->where($arg, 'like', '%' . $key . '%');
        }

        // 添加类型条件
        if ($type !== '-1' && !empty($type)) {
            $query->where('ITEM_TYPE', '=', $type);
        }

        // 执行查询并获取结果
        $list = $query->select();
        $count = $query->count();


        $json = [];
        foreach ($list as $k => $v) {
            $json[$k] = [
                'ITEM_ID' => $v['ITEM_ID'],
                'NAME' => $v['NAME'],
                'ITEM_ING' => getIiemImg($v['ITEM_INDEX'], $v['ITEM_CODE']),
                'ITEM_CODE' => $v['ITEM_CODE'],
                'ITEM_INDEX' => $v['ITEM_INDEX'],
                'ITEM_TYPE' => cfItemType($v['ITEM_TYPE']),
                'ITEM_CATEGORY1' => cfItemType1($v['ITEM_TYPE'], $v['ITEM_CATEGORY1']),
                'ITEM_CATEGORY2' => cfItemType2($v['ITEM_TYPE'], $v['ITEM_CATEGORY2']),
                'SHORT_NAME' => $v['SHORT_NAME'],
                'SHORT_DESCR' => $v['SHORT_DESCR'],
            ];
        }
        return json([
            'code' => 0,
            'count' => $count,
            'data' => $json
        ]);
    }

    public function PostWeb()
    {
        foreach (input('post.') as $k => $value) {
            Configs::where('id', $k)->update([
                'value' => $value
            ]);
        }
        AdminLog::insert([
            'name' => session('admin_name'),
            'content' => '管理员“' . session('admin_name') . '”更新了网站基础信息',
            'type' => 3,
            'ip' => Request::ip(),
            'create_time' => time()
        ]);
        $result = [
            'code' => 200,
            'msg' => '保存成功'
        ];
        return json($result);
    }

    public function AddInvitation()
    {
        $number = input('num');
        $cdKeys = [];
        $saveData = [];


        for ($start = 0; $start < $number; $start++) {
            $cdKeys[$start] = generateSurvivalCDK(32);
            $saveData[$start]['username'] = '';
            $saveData[$start]['status'] = 0;
            $saveData[$start]['code'] = $cdKeys[$start];
        }
        $cacheKey = md5(uniqid());

        cache("showNewInvitationCache:" . $cacheKey, $cdKeys, 180);
        (new Invitation())->saveAll($saveData);
        AdminLog::insert([
            'name' => session('admin_name'),
            'content' => '管理员“' . session('admin_name') . '”生成了' . $number . '张邀请码',
            'type' => 3,
            'ip' => Request::ip(),
            'create_time' => time()
        ]);
        return json(['code' => 200, 'msg' => '添加成功', 'key' => $cacheKey]);
    }

    public function Addagencycode(){
        $number = input('num');
        $cdKeys = [];
        $saveData = [];


        for ($start = 0; $start < $number; $start++) {
            $cdKeys[$start] = generateSurvivalCDK(32);
            $saveData[$start]['uid'] = '';
            $saveData[$start]['username'] = '';
            $saveData[$start]['status'] = 0;
            $saveData[$start]['code'] = $cdKeys[$start];
        }
        $cacheKey = md5(uniqid());

        cache("showNewagencycodeCache:" . $cacheKey, $cdKeys, 180);
        (new AgencyCode())->saveAll($saveData);
        AdminLog::insert([
            'name' => session('admin_name'),
            'content' => '管理员“' . session('admin_name') . '”生成了' . $number . '张代理邀请码',
            'type' => 3,
            'ip' => Request::ip(),
            'create_time' => time()
        ]);
        return json(['code' => 200, 'msg' => '添加成功', 'key' => $cacheKey]);
    }

    public function getApiKey()
    {
        $key = getToken(32);
        Admins::update([
            'token' => $key,
        ], [
            ['id', '=', session('admin_id')]
        ]);
        return json(['code' => 200, 'msg' => 'success', 'data' => [
            'key' => $key
        ]]);

    }

    public function AddCdkPost()
    {
        if (empty(input('typeId')) || empty(input('num')) || empty(input('item'))) {
            return json([
                'code' => 500,
                'msg' => '所有选项都不能为空'
            ]);
        }

        $pid = input('item');
        $number = input('num');
        $cdKeys = [];
        $saveData = [];

        for ($start = 0; $start < $number; $start++) {
            $cdKeys[$start] = generateSurvivalCDK(32);
            $saveData[$start]['name'] = 0;
            $saveData[$start]['status'] = 0;
            $saveData[$start]['type'] = input('typeId');
            $saveData[$start]['code'] = $cdKeys[$start];
            $saveData[$start]['item'] = $pid;

        }
        $cacheKey = md5(uniqid());

        cache("cdKeyCache:" . $cacheKey, $cdKeys, 180);
        (new Cdks())->saveAll($saveData);
        AdminLog::insert([
            'name' => session('admin_name'),
            'content' => '管理员“' . session('admin_name') . '”生成了' . $number . '张Cdk',
            'type' => 3,
            'ip' => Request::ip(),
            'create_time' => time()
        ]);
        return json(['code' => 200, 'msg' => '添加成功', 'key' => $cacheKey]);

    }

    public function CdkList()
    {

        $page = input('page', 1);
        $limit = input('limit', 10);
        $keyword = input('key'); // 获取搜索关键字

        // 初始化查询构建器
        $query = Cdks::order('create_time', 'DESC');

        // 如果存在关键字，则添加模糊搜索条件
        if (!empty($keyword)) {
            $query->where('code', 'like', '%' . $keyword . '%');
        }





        // 分页查询数据
        $result = $query->page($page, $limit)->select();
        $countQuery = clone $query;
        $count = $countQuery->count();

        return json([
            'code' => 0,
            'count' => $count,
            'data' => $result
        ]);
    }

    public function InviteLogList(){
        $page = (int)input('page', '1', 'trim');
        $limit = (int)input('limit', '10', 'trim');
        $key = input('key');
        $arg = input('arg');

        // 验证输入参数
        if ($page < 1 || $limit < 1) {
            return json(['code' => 1, 'msg' => 'Invalid page or limit']);
        }
        $query = InviteLog::order('create_time', 'DESC');
        if (!empty($key)) {
            $query->where($arg, 'like', '%' . $key . '%');
        }
        $list = $query->select();
        $count = $query->count();
        return json([
            'code' => 0,
            'count' => $count,
            'data' => $list
        ]);
    }

    public function UserLogList(){
        $page = (int)input('page', '1', 'trim');
        $limit = (int)input('limit', '10', 'trim');
        $arg = input('arg');
        $key = input('key');

        // 验证输入参数
        if ($page < 1 || $limit < 1) {
            return json(['code' => 1, 'msg' => 'Invalid page or limit']);
        }
        $query = UserLog::order('create_time', 'DESC');
        if (!empty($key)) {
            $query->where($arg, 'like', '%' . $key . '%');
        }
        $list = $query->select();
        $count = $query->count();
        return json([
            'code' => 0,
            'count' => $count,
            'data' => $list
        ]);
    }

    public function CdkViewList()
    {
        $id = input('id');

        $query = Cdks::where('id', $id)->find();

        if (!$query) {
            return json([
                'code' => 1,
                'msg' => '未找到对应记录',
                'data' => []
            ]);
        }

        $itemsStr = $query->item;
        $items = explode(',', $itemsStr);
        $list = [];
        foreach ($items as $item) {
            $item = trim($item);
            $list[] = ['item' => $item];
        }
        return json([
            'code' => 0,
            'count' => count($list),
            'data' => $list
        ]);
    }

    public function examinePost()
    {
        $id = input('id');
        $res = Report::where('id', $id)->find();
        if (!$res) {
            return json([
                'code' => 500,
                'msg' => '未找到记录'
            ]);
        } else {
            if ($res['reportType'] == 1) {
                Db::connect('game_db')->table('CF_USER')
                    ->where('USN', $res['rusn'])
                    ->update([
                        'HOLD_TYPE' => 'E'
                    ]);
            } elseif ($res['reportType'] == 2) {
                Db::connect('game_db')->table('CF_USER')
                    ->where('USN', $res['usn'])
                    ->update([
                        'HOLD_TYPE' => 'A'
                    ]);
            }
            Report::where('id', $id)->update([
                'status' => 1,

            ]);

            return json([
                'code' => 200,
                'msg' => '审核成功'
            ]);
        }
    }

    public function getRegStatus()
    {
        $res = Configs::where('id', input('id'))->update([
            'value' => input('status')
        ]);

        if ($res) {
            return json([
                'code' => 200,
                'msg' => '修改状态成功'
            ]);
        }
    }

    public function getCdkStatus()
    {
        $res = Configs::where('id', input('id'))->update([
            'value' => input('status')
        ]);

        if ($res) {
            return json([
                'code' => 200,
                'msg' => '修改状态成功'
            ]);
        }
    }

    public function getLoginStatus()
    {
        $res = Configs::where('id', input('id'))->update([
            'value' => input('status')
        ]);

        if ($res) {
            return json([
                'code' => 200,
                'msg' => '修改状态成功'
            ]);
        }
    }

    public function getJieStatus()
    {
        $res = Configs::where('id', input('id'))->update([
            'value' => input('status')
        ]);

        if ($res) {
            return json([
                'code' => 200,
                'msg' => '修改状态成功'
            ]);
        }
    }

    public function getItemStatus()
    {
        $res = Configs::where('id', input('id'))->update([
            'value' => input('status')
        ]);

        if ($res) {
            return json([
                'code' => 200,
                'msg' => '修改状态成功'
            ]);
        }
    }


    public function Auth(\think\Request $request)
    {
        $domain = Request::host();
        $res = curl('https://api.houz.cn/ajax/update?domain=' . $domain . '&pid=' . $request->pid . '&ver=' . $request->ver);
        $res = json_decode($res, true);
        if ($res['code'] == 200) {
            return json([
                'code' => 200,
                'data' => [
                    'title' => $res['data']['title'],
                    'ver' => $res['data']['ver'],
                    'content' => $res['data']['content'],
                    'create_time' => $res['data']['create_time'],
                ]
            ]);
        } else if ($res['code'] == 501) {
            return json([
                'code' => 501,
                'msg' => '没有查询到任何信息'
            ]);
        } else if ($res['code'] == 201) {
            return json([
                'code' => 201,
                'msg' => '当前为最新版本'
            ]);
        } else if ($res['code'] == 500) {
            return json([
                'code' => 201,
                'msg' => '域名无授权，请联系管理员'
            ]);
        }
    }

    public function update(\think\Request $request)
    {
        $domain = Request::host();
        $res = curl('https://api.houz.cn/ajax/update?domain=' . $domain . '&pid=' . $request->pid . '&ver=' . $request->ver);
        $res = json_decode($res, true);
        if ($res['code'] == 200) {
            $downloadUrl = 'https://api.houz.cn/' . $res['data']['package'];
            $tempDir = root_path() . '/runtime/temp/';
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0777, true);
            }
            $zipFile = $tempDir . 'package.zip';
            $client = new Client([
                'base_uri' => 'https://api.houz.cn/',
                'verify' => false, // 注意：不推荐在生产环境中使用
            ]);
            try {
                $dbPrefix = 'cf_';
                $response = $client->get($downloadUrl, [
                    'sink' => fopen($zipFile, 'wb'), // 将ZIP文件保存到本地
                ]);

                if ($response->getStatusCode() == 200) {
                    if (!empty($res['data']['upsqlall'])) {
                        $sqlStatements = explode(';', $res['data']['upsqlall']);
                        foreach ($sqlStatements as $sql) {
                            $sql = trim($sql);
                            if (empty($sql)) {
                                continue;
                            }
                            $sql = str_replace('{db_prefix}', $dbPrefix, $sql);
                            Db::execute($sql);
                        }
                    }

                    $extractDir = root_path();
                    $zip = new ZipArchive();
                    if ($zip->open($zipFile) === true) {
                        $zip->extractTo($extractDir);
                        $zip->close();
                        unlink($zipFile);
                        return json([
                            'code' => 200,
                            'msg' => '更新成功'
                        ]);
                    } else {
                        return json([
                            'code' => 201,
                            'msg' => '无法解压更新包'
                        ]);
                    }
                } else {
                    // 输出错误信息
                    return json([
                        'code' => 203,
                        'msg' => '更新失败，状态码：' . $response->getStatusCode()
                    ]);
                }
            } catch (\Exception $e) {
                return json([
                    'code' => 500,
                    'msg' => '发生错误：' . $e->getMessage()
                ]);
            }
        } else {
            return json([
                'code' => 501,
                'msg' => '无版本更新'
            ]);
        }
    }


}