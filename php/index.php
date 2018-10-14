<?php
/*

author:SZTU_1013 - Lin

version 1.0

*/
	include("function.php");
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if ($_POST['username']=="" & $_POST['password']=="") {
			$text="用户名和密码不能为空！！！";
		}else{
			$login=login($_POST['username'],$_POST['password']);
			if($login=="1"){
				$date=time();
				set_cookie($_POST['username'],$date);
				$url="control.php";
			}else{
				$text="登录失败！！！";
			}
		}
	}else{
		$check=check_login();
		if ($check=='1') {
			$url="control.php";
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>树莓派</title>
	<link rel="stylesheet" href="https://unpkg.com/purecss@0.6.1/build/pure-min.css" integrity="sha384-CCTZv2q9I9m3UOxRLaJneXrrqKwUNOzZ6NGEUMwHtShDJ+nCoiXJCAgi05KfkLGY" crossorigin="anonymous">
	<!--[if lte IE 8]>
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/grids-responsive-old-ie-min.css">
	<![endif]-->
	<!--[if gt IE 8]><!-->
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/grids-responsive-min.css">
	<!--<![endif]-->
	<?php if(!empty($text)){alert($text);} if(!empty($url)){to_other_url($url);} ?>
</head>
<body>
	<div class="pure-g">
	    <div class="pure-u-1 pure-u-md-1-3"></div>
	    <div class="pure-u-1 pure-u-md-1-3" align="center">
	    	<form class="pure-form pure-form-aligned" method="POST">
    			<fieldset>
    				<legend><font size="6">登录</font></legend>

			        <div class="pure-control-group" align="left">
			            <label for="name">用户名:</label>
			            <input id="name" type="text" placeholder="用户名" name="username">
			        </div>

			        <div class="pure-control-group" align="left">
			            <label for="password">密码：</label>
			            <input id="password" type="password" placeholder="密码" name="password">
			        </div>

			        <input type="submit" class="pure-button pure-button-primary" align="right" name="login" value="登录"></input>
			        </div>
    			</fieldset>
			</form>
	    </div>
	    <div class="pure-u-1 pure-u-md-1-3"></div>
	</div>
</form>
</body>
</html>
