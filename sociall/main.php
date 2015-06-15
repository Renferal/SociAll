<?php 
session_start();
if (!isset($_SESSION['login'])) {
	header('Location: index.php');
}
else {
require('include/http.php');
require('include/oauth_client.php');


$clientFB = new oauth_client_class;
	$clientFB->debug = false;
	$clientFB->debug_http = true;
	$clientFB->server = 'Facebook';
	$clientFB->redirect_uri = 'http://sociall.local/main.php';
	$clientFB->client_id ='1598187680456122'; $application_line = __LINE__;
	$clientFB->client_secret = '1d1d42da863532ddd035c30ce7d60ecb';
	if(strlen($clientFB->client_id) == 0
	|| strlen($clientFB->client_secret) == 0)
		die('Please go to Facebook Apps page https://developers.facebook.com/apps , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to App ID/API Key and client_secret with App Secret');
	/* API permissions
	 */
	$clientFB->scope = 'email,publish_actions,user_friends,read_stream';
	if(($successFB = $clientFB->Initialize()))
	{
		if(($successFB = $clientFB->Process()))
		{
			if(strlen($clientFB->access_token))
			{
				$successFB = $clientFB->CallAPI(
					'https://graph.facebook.com/v2.3/me', 
					'GET', array(), array('FailOnAccessError'=>true), $userFB);
				if($successFB)
				{
				
					
		
					if ($successFB)
					{
						$param= array(
							'filter'=>'app_2915120374'
						);
						$successFB= $clientFB->CallAPI(
							'https://graph.facebook.com/v2.3/me/feed',
							'GET', array('count'=>10), array('FailOnAccessError'=>true), $resultFB);
					}
				}
			}

		}
		$successFB = $clientFB->Finalize($successFB);
	}
	if($clientFB->exit)
		exit;
	$urlFB=array( 'url1'=>$resultFB->data[0]->actions[0]->link,
                      'url2'=>$resultFB->data[1]->actions[0]->link,
                      'url3'=>$resultFB->data[2]->actions[0]->link,
                      'url4'=>$resultFB->data[4]->actions[0]->link,
                      'url5'=>$resultFB->data[5]->actions[0]->link,
		      'url6'=>$resultFB->data[6]->actions[0]->link,
                      'url7'=>$resultFB->data[7]->actions[0]->link,
		      'url8'=>$resultFB->data[8]->actions[0]->link,
		      'ulr9'=>$resultFB->data[9]->actions[0]->link);

//A questo punto in $resultFB ho i post di facebook in formato JSON e il token salvato in sessione

$clientTW = new oauth_client_class;
$clientTW->debug = false;
$clientTW->debug_http = true;
$clientTW->server = 'Twitter';
$clientTW->redirect_uri = 'http://sociall.local/main.php';
$clientTW->client_id = 'MNOXzdJz2P0cd6WVwaPEZ0jtA'; $application_line = __LINE__;
$clientTW->client_secret = '2iattm0NpFFn5cDXplamqCiKkjY8192NvBwHDrbPUAz75cf2oe';
	if(($successTW = $clientTW->Initialize()))
	{
		if(($successTW = $clientTW->Process()))
		{
			if(strlen($clientTW->access_token))
			{
			$successTW = $clientTW->CallAPI(
					'https://api.twitter.com/1.1/account/verify_credentials.json', 
					'GET', array(), array('FailOnAccessError'=>true), $userTW);		

				if ($successTW) {
					
					$successTW=$clientTW->CallAPI(
                                        	'https://api.twitter.com/1.1/statuses/home_timeline.json',
                                        	'GET',array('screen_name'=>$userTW->screen_name, 'count'=>10), array('FailOnAccesError'=>true) , $statiTW);
					

					if ($successTW) {
						$successTW=!$successTW;
						
						for ($k=0;$k<10;$k++) {
							$id=$statiTW[$k]->id;
							$successTW|=$clientTW->CallAPI(
								'https://api.twitter.com/1.1/statuses/oembed.json',
								'GET',array('id'=>$id), array('FailOnAccessError'=>true) , $tweets[$k]);
						}
					}
				}
			}
		}
	$successTW = $clientTW->Finalize($successTW);
	}
	if($clientTW->exit)
		exit;
//Instagram
 $clientIN = new oauth_client_class;
 $clientIN->debug = false;
 $clientIN->debug_http = true;
 $clientIN->server = 'Instagram';
 $clientIN->redirect_uri = 'http://sociAll.local/main.php';

 $clientIN->client_id = 'ee5326222ada4b0094701c952a3e9f56'; $application_line = __LINE__;
 $clientIN->client_secret = '615390deacd1460fb90455f14f98354e';

        if(strlen($clientIN->client_id) == 0
        || strlen($clientIN->client_secret) == 0)
                die('Please go to Instagram Apps page http://instagram.com/developer/register/ , '.
                        'create an application, and in the line '.$application_line.
                        ' set the clientIN_id to clientIN id key and clientIN_secret with clientIN secret');

 $clientIN->scope = 'basic';
        if(($successIN = $clientIN->Initialize()))
        {
                if(($successIN = $clientIN->Process()))
                {
                        if(strlen($clientIN->access_token))
                        {
                                $successIN = $clientIN->CallAPI(
                                        'https://api.instagram.com/v1/users/self/', 
                                        'GET', array(), array('FailOnAccessError'=>true), $userIN);

                                if ($successIN) {
                                        $successIN=$clientIN->CallAPI(
                                                'https://api.instagram.com/v1/users/self/feed/',
                                                'GET',array(),array('FailOnAccessError'=>true),$feedIN);

	                                        if ($successIN) {
							$successIN=!$successIN;
							for ($i=0;$i<10;$i++) {
                                                	$linkIN=$feedIN->data[$i]->link;
						
                                                	$successIN|=$clientIN->CallAPI(
                                                        	'https://api.instagram.com/oembed/',
                                                        	'GET',array('url'=>$linkIN),array('FailOnAccessError'=>true),$postIN[$i]);
							}
                                        }

                                }


                        }
                }
                $successIN = $clientIN->Finalize($successIN);
        }
        if($clientIN->exit)
                exit;

if($successFB || $successTW || $successIN) {
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet" href="layout.css" type="text/css">

<title>SociAll</title>
</head>
<body>
<script>window.twttr = (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0],
    t = window.twttr || {};
  if (d.getElementById(id)) return t;
  js = d.createElement(s);
  js.id = id;
  js.src = "https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);
 
  t._e = [];
  t.ready = function(f) {
    t._e.push(f);
  };
 
  return t;
}(document, "script", "twitter-wjs"));</script>

 
<script>
window.fbAsyncInit = function() {
        FB.init({
          appId      : '1598187680456122',
          xfbml      : true,
          version    : 'v2.3'
        });
      };

      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "//connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
    </script>
<div id="title">
<img src="style/shout.png" />
</div>
<br><br>
<form action="shout.php" method="POST">
<table class="tabella" align="center">
<tr><td><textarea onfocus="this.value=''" name="message" class="shoutbox">
Enter some text...
</textarea></td></tr>

<tr><td><button type="submit" value="SHOUT" class="buttonS">
<b>SHOUT</b>
</button>
 
</td></tr>
</table>
</form>
<div id="logout">
<a href="logout.php" style="float: inherit; width:18% ;height:50px;" ><button class="buttonL"><b>Logout</b></button></a>
</div>
<table>
<tr>
<?php if($successFB) { ?>
<div id="contFB">
	
	<div class="titolo"> <img src="style/Facebook.png" width="100%"/> </div>
	<br>
	<div class="fb-post" data-href="<?php echo $urlFB['url1'];?>"  ></div>
	<div class="fb-post" data-href="<?php echo $urlFB['url2'];?>"  ></div>
	<div class="fb-post" data-href="<?php echo $urlFB['url3'];?>"  ></div>
	<div class="fb-post" data-href="<?php echo $urlFB['url4'];?>"  ></div>
	<div class="fb-post" data-href="<?php echo $urlFB['url5'];?>"  ></div> 
	<div class="fb-post" data-href="<?php echo $urlFB['url6'];?>"  ></div>
	<div class="fb-post" data-href="<?php echo $urlFB['url7'];?>"  ></div>
	<div class="fb-post" data-href="<?php echo $urlFB['url8'];?>"  ></div>
	<div class="fb-post" data-href="<?php echo $urlFB['url9'];?>"  ></div>
</div>
<?php  } 
	if ($successTW) {
?>
<div id="contTW">

	<div class="titolo"> <img width="100%" src="style/Twitter.png" style="height: 125px; margin: 0px auto; width: 100%;"> </div>
	<?php   echo $tweets[0]->html;
		echo $tweets[1]->html;
		echo $tweets[2]->html;
		echo $tweets[3]->html;
		echo $tweets[4]->html;
		echo $tweets[5]->html;
		echo $tweets[6]->html;
		echo $tweets[7]->html;
		echo $tweets[8]->html;
		echo $tweets[9]->html;
	?>
</div>
<?php }
	if ($successIN) {  ?>
<div id="contIN">
	<div class="titolo"> <img src="style/Instagram.png" width="100%"/> </div>	
	<?php 
		echo $postIN[0]->html;
		echo $postIN[1]->html;
		echo $postIN[2]->html;
		echo $postIN[3]->html;
		echo $postIN[4]->html;
	?>
</div>
<?php } ?>
<tr>
</table>

          
</body>
</html>
<?php
        }
        else
        {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>OAuth client error</title>
</head>
<body>
<h1>OAuth client error</h1>
<pre>Error: <?php echo HtmlSpecialChars($clientTW->error); ?></pre>
<pre>Error: <?php echo HtmlSpecialChars($clientFB->error); ?></pre>
<pre>Error: <?php echo HtmlSpecialChars($clientIN->error); ?></pre>
</body>
</html>
<?php
        }
}
?>

