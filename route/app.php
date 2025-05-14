<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;
//主页
Route::get('news', 'index/news');
Route::get('shop', 'index/shop');
Route::get('signin', 'index/signin');
Route::get('signup', 'index/signup');
Route::get('events', 'index/events');
Route::get('logout', 'Post/logout');
Route::get('article/:id', 'index/article');
Route::get('Activity/:id', 'index/Activity');
Route::get('report','index/report');
Route::get('ItemSendUser','index/ItemSendUser');
//会员中心
Route::get('profile', 'user/profile');
Route::get('cdk', 'user/cdk');
Route::get('Invite', 'user/Invite');
Route::get('ChangeName', 'user/ChangeName');
//后台登录验证
Route::post('PostAdminLogin', 'Post/PostAdminLogin');

Route::get('/admin/editNews/:id', 'admin/editNews');
Route::get('/admin/editEvents/:id', 'admin/editEvents');
Route::get("admin/showNewInvitation/:key", 'admin/showNewInvitation');
Route::get("admin/showNewagency/:key", 'admin/showNewagency');
Route::get("admin/showNewCdkey/:key", 'admin/showNewCdkey');

//代理后台
Route::get('Agency/login', 'index/agenlogin');
Route::get('PayAjax/notify', 'PostAgency/notify');
Route::get('PayAjax/return', 'PostAgency/return');