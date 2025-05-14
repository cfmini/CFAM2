<?php
/**
 *
 * @Author: 会飞的鱼
 * @Date: 2024/4/4 0004
 * @Time: 16:06
 * @Blog：https://houz.cn/
 * @Description: 会飞的鱼作品,禁止修改版权违者必究。
 */


namespace app\validate;

use think\Validate;

class UserV extends Validate
{
    protected $rule = [
        'username' => 'require|min:6|max:12|regex:/^(?=.*[a-zA-Z])(?=.*[0-9]).+$/',
        'password' => 'require|min:6|max:18',
        'password2' => 'require|confirm:password',
    ];

    protected $message = [
        'username.require' => '用户名不能为空',
        'username.min' => '用户名必须大于6位',
        'username.max' => '用户名必须小于12位',
        'username.regex' => '用户名必须包含字母和数字',
        'password.require' => '密码不能为空',
        'password.min' => '密码不能小于6位且大于18位',
        'password2.require' => '确认密码不能为空',
        'password2.confirm' => '两次输入的密码不一致',
    ];

    protected $scene = [
        'sendCode' => ['email'],
        'updateCode' => ['email'],
        'setPassword' => ['old_password', 'password'],
        'useMailSetPassword' => ['email', 'password'],
        'down_file' => ['email','url'],
        'signUp' => ['email', 'password'],
        'signIn' => ['email', 'password']
    ];

}