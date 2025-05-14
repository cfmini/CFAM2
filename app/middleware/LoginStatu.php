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


class LoginStatu
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
        if (!session('USER_LOGIN_ID')) {
            return redirect("/signin");
        }

        return $next($request);
    }
}