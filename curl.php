<?php
// curl的get
// 请求的URL地址
$url = 'http://localhost:8080/demo.html';
#$url = 'https://wx.1314000.cn/';
// CURL进行请求，可以发为4步走
// 初始化 相当于打开了浏览器
$ch = curl_init();
// 相关的设置
// 请求的URL地址设置
curl_setopt($ch,CURLOPT_URL,$url);
// 设置输出的信息不直接输出
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
// 取消https证书验证
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
// 设置请求的超时时间 单是秒
curl_setopt($ch,CURLOPT_TIMEOUT,10);
// 模拟一个浏览器型号
curl_setopt($ch,CURLOPT_USERAGENT,'MSIE');

// 告诉curl你使用了post请求
curl_setopt($ch,CURLOPT_POST,1);
// post的数据
curl_setopt($ch,CURLOPT_POSTFIELDS,['id'=>1,'name'=>'张三']);

// 执行请求操作
$data = curl_exec($ch);
// 得到请求的错误码  0返回请求成功，大于0表示请求有异常
$errno = curl_errno($ch);
if (0 < $errno) {
    // 抛出自己的异常
    throw new Exception(curl_error($ch), 1000);
}
// 关闭资源
curl_close($ch);
echo $data;


/*# 正则
$preg = '/<h3>.*<\/h3>/';

// 匹配结果
preg_match($preg,$data,$arr);

// 输出
var_dump($arr);
*/



