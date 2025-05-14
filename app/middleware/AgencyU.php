<?php
/**
 *
 * @Author: 会飞的鱼
 * @Date: 2024/4/5 0005
 * @Time: 15:27
 * @Blog：https://houz.cn/
 * @Description: 会飞的鱼作品,禁止修改版权违者必究。
 */


namespace app\middleware;


use app\model\AgencyUser;
use app\model\UserLog;
use think\facade\View;

class AgencyU
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
        if (!session('Agency_LOGIN_ID')) {
            return redirect("/Agency/login");
        }

        $user = AgencyUser::where('id', session('Agency_LOGIN_ID'))->find();
        View::assign([
            'userinfo' => $user
        ]);

        return $next($request);
    }
}