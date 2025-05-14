<?php
/**
 *
 * @Author: 会飞的鱼
 * @Date: 2024/4/1 0001
 * @Time: 20:00
 * @Blog：https://houz.cn/
 * @Description: 会飞的鱼作品,禁止修改版权违者必究。
 */
declare (strict_types=1);
namespace app\model;

use think\Model;
class AgencyUserLog extends Model
{
    protected $createTime = 'create_time';
    protected $updateTime = false;

    /**
     * 获取今日注册用户数量
     *
     * @return int
     */
    public function getTodayRegistrationCount()
    {
        $todayStart = strtotime(date('Y-m-d') . ' 00:00:00');
        $todayEnd = strtotime(date('Y-m-d') . ' 23:59:59') + 86399;


        return $this->whereBetween('create_time', [$todayStart, $todayEnd])->count();
    }
}