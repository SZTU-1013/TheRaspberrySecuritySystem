<?php
/*

author:SZTU_1013 - Lin

version 1.0

*/
  include("function.php");
  $check=check_login();
  if ($check=='1') {
    if (!empty($_POST['control'])) {
      if ($_POST['control']=="布放") {
        $send=set_state("Setup");
        if($send=='1'){
          $text="设置成功";
        }else{
          $text="设置失败";
        }
      }elseif($_POST['control']=='撤防'){
        $send=set_state("Withdraw");
        if($send=='1'){
          $text="设置成功";
        }else{
          $text="设置失败";
        }
      }else{
        $text="有问题";
      }
    }
    $state=get_state();#0为撤放，1为布放
    if ($state=='{"state": 0}'){
        $set='pure-button-active';
        $wid='pure-button-disabled';
    }elseif($state=='{"state": 1}'){
        $set='pure-button-disabled';
        $wid='pure-button-active';
    }else{
        $wid='pure-button-disabled';
        $set='pure-button-disabled';
        $text="Python服务出错";
    }
  }else{
    $url="index.php";
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
    <center>
        <h2>安防控制</h2>
    </center>
    <form name="form" method="POST">
      <center> 
          <input style="width : 175px;height:75px" type="submit" value="布放" name="control" class=<?php echo "\"pure-button ".$set."\""; ?>>
      </center>
      <br>
      <center>
          <input style="width : 175px;height:75px" type="submit" value="撤防" name="control" class=<?php echo "\"pure-button ".$wid."\""; ?>>
      </center>
    </form>
  </body>
</html>
