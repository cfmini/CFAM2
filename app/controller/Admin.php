<?php
/**
 *
 * @Author: 会飞的鱼
 * @Date: 2024/4/12 0012
 * @Time: 8:01
 * @Blog：https://houz.cn/
 * @Description: 会飞的鱼作品,禁止修改版权违者必究。
 */


namespace app\controller;
use app\middleware\AdminStatus;
use app\middleware\BaseMiddleware;
use app\model\AdminLog;
use app\model\Admins;
use app\model\Events;
use app\model\News;
use app\model\Shop;
use app\model\UserLog;
use think\facade\Db;
use think\Request;
use think\facade\Session;
use think\facade\View;

class Admin
{

    protected $middleware = [
        AdminStatus::class,
        BaseMiddleware::class
    ];

    public function logout(){
        Admins::where('id',Session('admin_id'))->update([
            'session'   =>  0
        ]);
        session::delete('admin_id');
        return redirect("/");
    }

    public function index(){

        return View();
    }

    public function update(){
        $this->checkPower();
        return View();
    }

    public function home(Request $request){

        //统计服务端物品数量
        $itemNum = Db::connect('game_db')->table('CF_ITEM_INFO')->count();
        //统计服务端注册用户数量
        $userNum = Db::connect('game_db')->table('CF_MEMBER')->count();
        //统计后台出售商品数量
        $shopNum = Shop::count();
        //统计今日注册用户数量，仅统计后台注册记录
        $userLogModel = new UserLog();
        $regCount = $userLogModel->getTodayRegistrationCount();
        //遍历管理员日记
        $adminLog = AdminLog::limit(20)->order('create_time','DESC')->select();
        //遍历活动
        $events = Events::limit(6)->order('create_time','DESC')->select();
        foreach ($events as &$event) {
            $event['title'] = mb_substr($event['title'], 0, 30, 'UTF-8');
            if (time() < $event['start_time']) {
                $event['status'] = '<span class="text-warning">未开始</span>';
            } elseif (time() > $event['end_time']) {
                $event['status'] = '<del class="text-muted">已结束</del>';
            } else {
                $event['status'] = '<span class="text-success">进行中</span>';
            }
        }
        //遍历在线管理员



        View::assign([
            'itemNum'   =>  $itemNum,
            'userNum'   =>  $userNum,
            'shopNum'   =>  $shopNum,
            'regNum'    =>  $regCount,
            'adminLog'  =>  $adminLog,
            'events'  =>  $events,
        ]);
        return View();
    }

    public function user(){
        return View();
    }

    public function password(){
        return View();
    }

    public function InviteLog(){
        return View();
    }

    public function userItem(){
        return View();
    }

    public function retpass(){
        View::assign('usn',input('id'));
        return View();
    }

    public function shop(){
        return View();
    }

    public function shopLog(){
        return View();
    }

    public function addNews(){
        return View();
    }

    public function itemAll(){
        return View();
    }

    public function news(){
        return View();
    }

    public function editNews($id){
        $res = News::where('id',$id)->find();
        View::assign('res',$res);
        return view();
    }

    public function editEvents($id){
        $res = Events::where('id',$id)->find();
        View::assign('res',$res);
        return view();
    }

    public function events(){
        return view();
    }

    public function addEvents(){
        return View();
    }

    public function EventsLog(){
        return View();
    }

    public function AdminUser(){
        $this->checkPower();
        return View();
    }

    function checkPower()
    {
        $request = request();
        if($request->User['rank']!==1){
            exit(View::fetch('admin/403'));
        }
    }

    public function web(){
        $this->checkPower();
        return View();
    }

    public function report(){
        return View();
    }

    public function Invitation(){
        return View();
    }

    public function showNewInvitation($key){
        $content = cache("showNewInvitationCache:" . $key);
        if (!$content) {
            return "请返回数据表查询";
        }
        View::assign('list', $content);
        return view();
    }

    public function showNewagency($key){
        $content = cache("showNewagencycodeCache:" . $key);
        if (!$content) {
            return "请返回数据表查询";
        }
        View::assign('list', $content);
        return view();
    }

    public function showNewCdkey($key){
        $content = cache("cdKeyCache:" . $key);
        if (!$content) {
            return "请返回数据表查询";
        }
        View::assign('list', $content);
        return view();
    }

    public function userinfo(){
        return view();
    }

    public function online(){
        return View();
    }

    public function CdkList(){
        return View();
    }

    public function record(){
        return View();
    }

    public function userLog(){
        return View();
    }

    public function regapi(){
        return View();
    }

    public function cdkapi(){
        return View();
    }

    public function loginapi(){
        return View();
    }

    public function jieapi(){
        return View();
    }

    public function itemapi(){
        return View();
    }

    public function agencyUser(){
        return View();
    }

    public function agencyCode(){
        return View();
    }

}