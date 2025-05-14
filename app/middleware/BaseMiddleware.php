<?php
/**
 *
 * User: 会飞的鱼
 * Date: 2023/7/29
 * QQ: 137691250
 * Email: <137691250@qq.com>
 */

namespace app\middleware;


use app\model\Admins;
use app\model\Configs;
use think\facade\Request;
use think\facade\View;
use think\response\Json;

class BaseMiddleware
{
    /**
     * 处理请求
     * 基础中间件
     * @param \think\Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $config = Configs::gets(true);
        View::assign([
            'configs'=> $config,
            'domain' => Request::domain()
        ]);
        return $next($request);
    }
}