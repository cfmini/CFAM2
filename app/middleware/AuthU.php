<?php
/**
 *
 * User: 会飞的鱼
 * Date: 2023/7/29
 * QQ: 137691250
 * Email: <137691250@qq.com>
 */

namespace app\middleware;



use app\model\UserLog;
use think\facade\Cache;
use think\facade\Request;
use think\facade\View;


class AuthU
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
        $domain = Request::host();
        $cacheResult = Cache::get('auth_result_for_' . $domain);
        if ($cacheResult === false || $cacheResult !== true) {
            $res = curl('https://api.houz.cn/ajax/AjaxAuth?domain='.$domain);
            $res = json_decode($res, true);

            if ($res['code'] == 1) {
                Cache::set('auth_result_for_' . $domain, true, 24 * 3600); // 缓存 24 小时

                $user = UserLog::where('userid', session('USER_LOGIN_ID'))->find();
                $user['email'] = empty($user['email']) ? null : $user['email'];
                View::assign([
                    'userUid' => session('USER_LOGIN_ID'),
                    'userinfo' => $user
                ]);
                return $next($request);
            } else {
                return redirect('https://houz.cn/post/761');
            }
        }else{
            $user = UserLog::where('userid', session('USER_LOGIN_ID'))->find();

            $user['email'] = empty($user['email']) ? null : $user['email'];
            View::assign([
                'userUid' => session('USER_LOGIN_ID'),
                'userinfo' => $user
            ]);
            return $next($request);
        }
    }
}