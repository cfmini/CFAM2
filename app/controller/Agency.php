<?php
/**
 *
 * @Author: 会飞的鱼
 * @Date: 2024/7/22 0022
 * @Time: 19:53
 * @Blog：https://houz.cn/
 * @Description: 会飞的鱼作品,禁止修改版权违者必究。
 */


namespace app\controller;


use app\middleware\AgencyU;
use app\model\AgencyCode;
use app\model\AgencyMoneyLog;
use app\model\AgencyNickCdk;
use app\model\AgencyOrder;
use app\model\AgencyShop;
use app\model\AgencyShopCdk;
use app\model\AgencyUser;
use app\model\AgencyUserLog;
use DateTime;
use think\facade\Db;
use think\facade\View;
use think\Model;
use think\Response;
use think\facade\Request;

class Agency
{

    protected $middleware =[
        AgencyU::class
    ];


    public function index(){
        $monyeNUm = AgencyOrder::where('uid',session('Agency_LOGIN_ID'))->whereOr('trade_status','TRADE_SUCCESS')->count();
        $userNUm = AgencyUserLog::where('uid',session('Agency_LOGIN_ID'))->count();
        $sunNum = round(AgencyMoneyLog::where('uid', session('Agency_LOGIN_ID'))->sum('money'), 2);
        $userLogModel = new AgencyUserLog();
        $regCount = $userLogModel->getTodayRegistrationCount();
        //$newUserCount = AgencyUserLog::where('uid', session('Agency_LOGIN_ID'))->getTodayRegistrationCount();

        $consumption = AgencyMoneyLog::where('uid',session('Agency_LOGIN_ID'))->order('create_time','DESC')->limit(10)->select();
        View::assign([
            'moneyNum'  =>  $monyeNUm,
            'userNUm'  =>  $userNUm,
            'sunNum'    =>  $sunNum,
            'newUserCount' =>  $regCount,
            'consumption'  =>  $consumption
        ]);
        return View();
    }

    public function pay(){
        return View();
    }

    public function order(){
        return View();
    }

    public function MoneyLog(){
        return View();
    }

    public function UserList(){
        return View();
    }

    public function shop(){
        return View();
    }

    public function Code(){
        return View();
    }

    public function userinfo(){
        return View();
    }

    public function Cdk(){
        return View();
    }

    public function NickCdk(){
        return View();
    }

    public function gift(){
        return View();
    }

    public function get_list_order(){
        $list = AgencyOrder::where('uid', session('Agency_LOGIN_ID'))->order('create_time','DESC')->select();
        // 准备返回的数据
        $data = [
            'client' => count($list),
            'rows' => $list
        ];

        return json($data);
    }

    public function get_list_shop(){
        $list = AgencyShop::where('status',1)->order('create_time','DESC')->select();

        $data = [
            'client' => count($list),
            'rows' => $list
        ];

        return json($data);
    }

    public function get_list_nick_cdk(){
        $list = AgencyNickCdk::where('uid',session('Agency_LOGIN_ID'))->order('create_time','ASC')->select();
        $json = [];
        foreach ($list as $k=>$v){

            $json[$k] = [
                'id'    =>  $v['id'],
                'username'  =>  $v['username'],
                'code'  =>  $v['code'],
                'status'    =>  $v['status'] == 0 ? '未使用':'已使用',
                'create_time'   =>  $v['create_time']
            ];

        }
        $data = [
            'client' => count($list),
            'rows' => $json
        ];

        return json($data);
    }

    public function get_list_money(){
        $list = AgencyMoneyLog::where('uid',session('Agency_LOGIN_ID'))->order('create_time','DESC')->select();

        $data = [
            'client' => count($list),
            'rows' => $list
        ];

        return json($data);
    }
    public function get_list_user(){
        $list = AgencyUserLog::where('uid',session('Agency_LOGIN_ID'))->order('create_time','DESC')->select();
        $json = [];
        foreach ($list as $k=>$v){
            $row =  Db::connect('game_db')->table('CF_MEMBER')->where('USER_ID', $v['user'])
                ->field('USN')
                ->find();
            $user = Db::connect('game_db')->table('CF_USER')->where('USN',$row['USN'])->find();
            set_error_handler(function($errno, $errstr, $errfile, $errline) {
            });
            if (!empty($user)){
                $nickss = iconv("GB18030", "UTF-8", iconv("UTF-8", "ISO-8859-1", $user['NICK']));
                restore_error_handler();
                $nick = empty($nickss) ? $user['NICK']:$nickss;
            } else {
                $nick = '未创建角色';
            }
            $lev = empty($user) ? '未创建角色' : $user['LEV'];
            $status = empty($user) ? '未创建角色' : $user['HOLD_TYPE'];
            $json[$k] = [
                'id'    =>  $v['id'],
                'user'  =>  $v['user'],
                'usn'  =>  $row['USN'],
                'email' =>  $v['email'],
                'nick' =>  $nick,
                'lev' =>  $lev,
                'status'    => $status,
                'create_time'   =>  $v['create_time'],
            ];
        }


        $data = [
            'client' => count($json),
            'rows' => $json
        ];

        return json($data);
    }

    public function get_list_code(){
        $list = AgencyCode::where('uid',session('Agency_LOGIN_ID'))->order('create_time','ASC')->select();

        $json = [];
        foreach ($list as $k=>$v){

            $json[$k] = [
                'id'    =>  $v['id'],
                'username'  =>  $v['username'],
                'code'  =>  $v['code'],
                'status'    =>  $v['status'] = 0 ? '已使用':'未使用',
                'create_time'   =>  $v['create_time']
            ];

        }
        $data = [
            'client' => count($list),
            'rows' => $json
        ];

        return json($data);
    }

    public function get_list_cdk(){
        $list = AgencyShopCdk::where('uid',session('Agency_LOGIN_ID'))->order('create_time','ASC')->select();

        $json = [];
        foreach ($list as $k=>$v){

            $json[$k] = [
                'id'    =>  $v['id'],
                'username'  =>  $v['username'],
                'code'  =>  $v['code'],
                'itemid'  =>  $v['itemid'],
                'status'    =>  $v['status'] = 0 ? '已使用':'未使用',
                'create_time'   =>  $v['create_time']
            ];

        }
        $data = [
            'client' => count($list),
            'rows' => $json
        ];

        return json($data);
    }

    public function get_reg(){
        $dates = getBeforeBetweenTime(7); // 假设这个函数返回最近7天的日期范围
        $basics = [];
        $labels = ['周一', '周二', '周三', '周四', '周五', '周六', '周日'];

        foreach ($dates as $value) {
            // 转换日期为星期几，调整为从星期一开始计数（1-7），星期天为7
            $dayOfWeek = (int)date('N', strtotime($value['date']));
            // 星期天应该对应 '周日'，所以我们将星期天的数字7调整为0
            $index = $dayOfWeek === 7 ? 0 : $dayOfWeek - 1;

            // 获取对应的星期标签
            $weekdayLabel = $labels[$index];

            // 统计对应星期标签的记录数
            $basics[$weekdayLabel] = AgencyUserLog::where('uid', session('Agency_LOGIN_ID'))->where('create_time', 'between', [$value['start'], $value['end']])->count();
        }

        // 创建结果数组，确保顺序与labels一致，并初始化为0
        $resultData = array_fill_keys($labels, 0);

        // 用实际的统计数据更新结果数组
        foreach ($basics as $label => $count) {
            if (isset($resultData[$label])) {
                $resultData[$label] = $count;
            }
        }

        return json([
            'code' => 200,
            'data' => ['labels' => $labels, 'data' => array_values($resultData)]
        ]);
    }

    public function get_money(){
        // 获取当前月份
        $currentMonth = (int)date('m');
        $basics = [];
        $labels = [];

        // 根据当前月份确定要显示的月份范围
        if ($currentMonth >= 7) {
            // 如果当前是7月至12月，显示6月至12月
            for ($month = 6; $month <= 12; $month++) {
                $labels[] = date('Y-' . str_pad($month, 2, '0', STR_PAD_LEFT));
            }
        } else {
            // 如果当前是1月至6月，显示1月至6月
            for ($month = 1; $month <= 6; $month++) {
                $labels[] = date('Y-' . str_pad($month, 2, '0', STR_PAD_LEFT));
            }
        }

        // 遍历每个月份，获取统计数据
        foreach ($labels as $label) {
            // 解析年份和月份
            list($year, $month) = explode('-', $label);
            // 为每个月创建开始和结束时间戳
            $startDate = strtotime($year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01 00:00:00');
            $endDate = ($month == 12) ? strtotime(($year + 1) . '-01-01 00:00:00') : strtotime($year . '-' . str_pad($month + 1, 2, '0', STR_PAD_LEFT) . '-01 00:00:00');
            $endDate = strtotime(date('Y-m-d 23:59:59', strtotime("-1 day", $endDate))); // 获取上一个月的最后一天的时间戳

            // 统计对应月份的金额
            $basics[$label] = round(
                AgencyMoneyLog::where('uid', session('Agency_LOGIN_ID'))
                    ->where('create_time', '>=', $startDate)
                    ->where('create_time', '<=', $endDate)
                    ->sum('money'),
                2
            );
        }

        // 创建结果数组，确保顺序与labels一致，并初始化为0
        $resultData = array_fill_keys($labels, 0);

        // 用实际的统计数据更新结果数组
        foreach ($basics as $label => $count) {
            if (isset($resultData[$label])) {
                $resultData[$label] = $count;
            }
        }

        return json([
            'code' => 200,
            'data' => ['labels' => $labels, 'data' => array_values($resultData)]
        ]);
    }

}