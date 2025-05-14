<?php
/**
 *
 * @Author: 会飞的鱼
 * @Date: 2024/4/12 0012
 * @Time: 8:03
 * @Blog：https://houz.cn/
 * @Description: 会飞的鱼作品,禁止修改版权违者必究。
 */


namespace app\middleware;


use app\model\Admins;
use think\facade\Cache;
use think\facade\View;
use think\facade\Request;
class AdminStatus
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {

        if (!session('admin_id')) {
            return redirect("/index/adminlogin");
        }
        $userinfo = Admins::where('id', session('admin_id'))->find();
        if (!$userinfo) {
            return json([
                'code' => 500,
                'msg' => '权限不足',
            ]);
        }
        if ($userinfo['status'] == 2){
            return json([
                'code' => 501,
                'msg' => '你账号已被禁用',
            ]);
        }

        $domain = Request::host();
        $cacheAdmin = Cache::get('auth_result_for_admin' . $domain);
        if ($cacheAdmin === false || $cacheAdmin !== true) {
            $res = curl('https://api.houz.cn/ajax/AjaxAuth?domain='.$domain);
            $res = json_decode($res, true);

            if ($res['code'] == 1) {
                Cache::set('auth_result_for_' . $domain, true, 24 * 3600); // 缓存 24 小时

                $ver = '1.8';
                $pid = '2';
                $request->ver = $ver;
                $request->pid = $pid;
                $request->User = $userinfo;
                View::assign([
                    'user'=>$request->User,
                    'ver'   =>  $ver,
                    'pid'   =>  $pid
                ]);
                return $next($request);
            } else {
                return redirect('https://houz.cn/post/761');
            }
        } else{
            $ver = '1.8';
            $pid = '2';
            $request->ver = $ver;
            $request->pid = $pid;
            $request->User = $userinfo;
            View::assign([
                'user'=>$request->User,
                'ver'   =>  $ver,
                'pid'   =>  $pid
            ]);
            return $next($request);
        }
    }
}