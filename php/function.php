<?php
/*

author:SZTU_1013 - Lin

version 1.0

*/

	/*
	登录功能模块-验证用户名和密码
	$user-用户名
	$pwd-密码
	*/
	function login($user,$pwd){
		$pwd=md5($pwd);
		if($user=="admin" & $pwd=='21232f297a57a5a743894a0e4a801fc3'){
			return "1";
		}else{
			return "0";
		}
	}

	/*
	Cookie设置
	*/
	function set_cookie($user,$date){
		$shuiji=uniqid();
		$cookie=md5($user.$date.$shuiji);
		setcookie('login',$cookie,$date+86400);
		session_set_cookie_params('86400'); 
		session_start();
		$_SESSION['date']=$date;
		$_SESSION['shuiji']=$shuiji;
		$_SESSION['user']=$user;
	}

	/*
	登录检查
	*/
	function check_login(){
		if (!empty($_COOKIE['login']) & !empty($_COOKIE['PHPSESSID'])) {
			$check_cookie=$_COOKIE['login'];
			session_start();
			if(!empty($_SESSION['date']) & !empty($_SESSION['shuiji']) & !empty($_SESSION['user'])){
				$date=$_SESSION['date'];
				$shuiji=$_SESSION['shuiji'];
				$user=$_SESSION['user'];
				$cookie=md5($user.$date.$shuiji);
				if ($check_cookie==$cookie) {
					return '1';
				}else{
					return '0';
				}
			}else{
				return '0';
			}
		}else{
			return '0';
		}
	}

	/*
	消息提醒
	*/
	function alert($text){
		if ($num='1') {
			echo "<script type=\"text/javascript\">alert(\"".$text."\")</script>";			
		}
	}

	/*
	页面跳转
	*/
	function to_other_url($url){
		header("Location:{$url}");
	}
	
	/*
	获取红外报警器状态
	*/
	function get_state(){
		$url='http://127.0.0.1:8888/?ask_state=1';
		$get_state=file_get_contents($url);
		return $get_state;
	}

	/*
	设置红外报警器状态
	*/
	function set_state($state){
		$date=array('control' => $state);
		$url='127.0.0.1:8888';
		return substr(send_post($date,$url), -1,1) ;
	}

	/*
	发送Post请求
	*/
	function send_post($date,$url){
		$curl=curl_init();	//初始化
		curl_setopt($curl,CURLOPT_URL,$url);	//设置抓取的url
		curl_setopt($curl, CURLOPT_HEADER, 1);	//设置获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	//设置获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($curl, CURLOPT_POST, 1);	//设置post方式提交
		curl_setopt($curl, CURLOPT_POSTFIELDS,$date);	//设置post数据
		$redate = curl_exec($curl);	//执行命令
		curl_close($curl);	//关闭URL请求
		return $redate;	//返回获得的数据
	}
if ($_SERVER['PHP_SELF']=='/function.php') {
	header('HTTP/1.1 404 Not Found');
	header("status: 404 Not Found");
}
?>