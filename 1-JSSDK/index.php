<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wxe97fa6dd70d513ff", "8cc5d56069f4f37f4f79daf952cb4e45");
$signPackage = $jssdk->GetSignPackage();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Wechat test</title>

    </head>
    
    <body>
    	<h1> wechat test </h1>
    	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" ></script>
    	<script>
		//injection authentication
		wx.config({
			debug: false,
			appId: '<?php echo $signPackage["appId"];?>',
			timestamp: <?php echo $signPackage["timestamp"];?>,
			nonceStr: '<?php echo $signPackage["nonceStr"];?>',
			signature: '<?php echo $signPackage["signature"];?>',
			// list all APIs you are going to call in jsApiList
			jsApiList: [
					'scanQRCode',
	                'onMenuShareTimeline',
	                'onMenuShareAppMessage',
				]
    	});

    	wx.ready(function () {
	        alert("Im fabulous and ready");
	        wx.onMenuShareAppMessage({
	            title: 'hello world', // 分享标题
	            desc: 'Im a description', // 分享描述
	            link: 'http://baidu.com', // 分享链接
	            imgUrl: 'images/squaredImage.png', // 分享图标
	            type: '', // 分享类型,music、video或link，不填默认为link
	            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
	            success: function () { 
	                alert("you shared successfully the link");
	            },
	            cancel: function () { 
	                alert("Y U DID NOT SHARED MY LINK?");
	            }
	        });
	        setTimeout(function(){
        		wx.scanQRCode ({
				    NeedResult: 0, 
				    ScanType: ["qrCode", "barCode"], 
				    Success: function (res) {
					    alert(JSON.stringify(res));
					}
				});
	        },3000);
	        
	    }); 
		</script>
    </body>


</html>
