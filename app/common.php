<?php
// 应用公共文件
use think\facade\Request;

function getIiemImg($item_index, $item_code){
    $strlen = strlen($item_index);
    if($strlen == 1){
        $item_index = '00'.$item_index;
    }
    if($strlen == 2){
        $item_index = '0'.$item_index;
    }
    $file = '/static/images/ITEMICON/ItemIcon_'.$item_index.'.png';

    return $file;

}
function cfItemType($t){
    $data = [
        'C' => '角色',
        'D' => '装备',
        'W' => '武器',
        'F' => '道具',
        'S' => '背包',
    ];
    return $data[$t] ?? $t;
}
function cfItemType1($t,$t2){
    $data = [
        'W' => [
            'M' => '主武器',
            'S' => '副武器',
            'K' => '近身武器',
            'D' => '投抛武器',
        ],
        'F' => [
            '2' => '装备',
        ],
    ];
    return $data[$t][$t2] ?? $t2;
}
function cfItemType2($t,$t2){
    $data = [
        'C' => '角色',
        'D' => [
            'name' => '装备',
            'SF' => '脸部',
            'SB' => '背部',
            'SH' => '头部',
            'SS' => '肩膀',
            'SW' => '腰部',
            'STL' => '腰部',
            'SFTP' => '透明眼镜',
            'SHTP' => '透明头盔',
            'TF' => '<font color="red">无效物品</font>',
        ],
        'W' => [
            'name' => '武器',
            'R' => '步枪',
            'SR' => '狙击枪',
            'SM' => '冲锋枪',
            'M' => '机枪',
            'S' => '散弹枪',
            'HE' => '手雷',
            'P' => '手枪',
            'SG' => '烟雾弹',
            'FB' => '闪光弹',
            'K' => '近身武器',

        ],
        'F' => [
            ''
        ],
    ];
    return $data[$t][$t2] ?? $t2;
}
function generateSurvivalCDK($length = 8) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $cdk = '';

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = mt_rand(0, strlen($characters) - 1);
        $cdk .= $characters[$randomIndex];
    }

    return $cdk;
}
function getToken($length = '7')
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet .= "0123456789";
    $max = strlen($codeAlphabet);
    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[random_int(0, $max - 1)];
    }
    return $token;
}
function curl($url){ //Curl GET
    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
    ]);

    $res = file_get_contents('https://api.houz.cn/ajax/AjaxAuth?domain='.Request::host(), false, $context);
    //$res = file_get_contents('https://api.houz.cn/ajax/AjaxAuth?domain='.Request::host());
    $res = json_decode($res, true);
    if ($res['code'] == 1){
        $ch = curl_init();     // Curl 初始化
        $timeout = 30;     // 超时时间：30s
        $ua='Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36';// 伪造抓取 UA
        $ip = mt_rand(11, 191) . "." . mt_rand(0, 240) . "." . mt_rand(1, 240) . "." . mt_rand(1, 240);
        curl_setopt($ch, CURLOPT_URL, $url);// 设置 Curl 目标
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);// Curl 请求有返回的值
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);// 设置抓取超时时间
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// 跟踪重定向
        curl_setopt($ch, CURLOPT_REFERER, 'https://www.baidu.com/');//模拟来路
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$ip, 'CLIENT-IP:'.$ip));  //伪造IP
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);// 伪造ua
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);// https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);//强制协议为1.0
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );//强制使用IPV4协议解析域名
        $content = curl_exec($ch);
        curl_close($ch);// 结束 Curl
        return $content;// 函数返回内容
    }else{

        return json_encode([
            'code' => 506,
            'msg' => 'Authentication failed'
        ]);
    }

}


function reverseIp($ip) {
    $parts = explode('.', $ip);
    return implode('.', array_reverse($parts));
}

function ipToUnsignedInt($ip) {
    $parts = explode('.', $ip);
    $int = 0;
    foreach ($parts as $part) {
        $int = ($int << 8) | intval($part);
    }
    // 如果需要的话，可以将结果转换为字符串以避免溢出导致的负数表示
    // 但对于大多数 PHP 版本和配置，64 位整数应该能够安全地存储这个值
    return $int;
}

function curls($url){ //Curl GET
    $ch = curl_init();     // Curl 初始化
    $timeout = 30;     // 超时时间：30s
    $ua='Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36';// 伪造抓取 UA
    $ip = mt_rand(11, 191) . "." . mt_rand(0, 240) . "." . mt_rand(1, 240) . "." . mt_rand(1, 240);
    curl_setopt($ch, CURLOPT_URL, $url);// 设置 Curl 目标
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);// Curl 请求有返回的值
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);// 设置抓取超时时间
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// 跟踪重定向
    curl_setopt($ch, CURLOPT_REFERER, 'https://www.baidu.com/');//模拟来路
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$ip, 'CLIENT-IP:'.$ip));  //伪造IP
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);// 伪造ua
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);// https请求 不验证证书和hosts
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);//强制协议为1.0
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );//强制使用IPV4协议解析域名
    $content = curl_exec($ch);
    curl_close($ch);// 结束 Curl
    return $content;// 函数返回内容
}
function getBeforeBetweenTime(int $day): array
{
    $resultTimes = [];
    for ($i = 0; $i < $day; $i++) {
        $currDayTime = strtotime(date("Y-m-d", strtotime("-{$i} day")));
        $resultTimes[$i]['start'] = $currDayTime;
        $resultTimes[$i]['date'] = date("Y-m-d", strtotime("-{$i} day"));
        $resultTimes[$i]['end'] = $currDayTime + 86400 - 1;
    }
    return $resultTimes;
}