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
		<title>Wechat Scanner App</title>
	  <!-- Compiled and minified CSS -->
      <link rel="stylesheet" href="css/materialize.min.css">
      <!-- Compiled and minified JavaScript -->

	</head>
	<style>
    	.container {
	        margin-bottom: 70px;
    	}
    	.wrapper nav .brand-logo {
    	    font-size: 17px;
    	}
	    .scan-button {
            position: fixed;
            bottom: 0px;
            height: 50px;
            width: 100%;
            line-height: 50px;
        }
        .card-panel {
            overflow:hidden;
        }
	</style>
	<body>

	<div class="wrapper">
    	 <nav>
            <div class="nav-wrapper">
              <a href="#" class="brand-logo">31TEN Wechat Scanner</a>
            </div>
          </nav>

    	<a class="scan-button waves-effect waves-light btn" id="scanQRCode1"><i class="material-icons left">aspect_ratio</i>Scan QRcode</a>

        <div class="container">
        	<div id="result" class="container">

        	</div>
    	</div>
	</div>

	<script src="js/jquery.js"></script>
	<script src="js/materialize.min.js"></script>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
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
        console.log("Im fabulous and ready");
        wx.onMenuShareAppMessage({
            title: 'Im wonderful bitch', // 分享标题
            desc: 'woooop', // 分享描述
            link: 'http://baidu.com', // 分享链接
            imgUrl: 'img/squaredImage.png', // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () { 
                alert("thanks for sharing, biatch");
            },
            cancel: function () { 
                alert("Y U CANCELLED THE SHARING?");
            }
        });
    }); 
    </script>
    <script>

        function generateBox(string){
            var html =  '<div class="col s12 m5">\
                            <div class="card-panel">\
                              <span class="blue-text text-darken-2">\
                                '+string+'\
                              </span>\
                            </div>\
                        </div>';
            $("#result").append(html);
        }



        $(document).ready(function(){
            generateBox("Click on the button scan to get QRcode information");

        });

        wx.ready(function () {
           // scanning result directly returned
        	document.querySelector('#scanQRCode1').onclick = function () {
        		wx.scanQRCode({
        			needResult: 1,
        			desc: 'scanQRCode desc',
        			success: function (res) {
        			    answer = res.resultStr;
        			    result = "";
        			    if( answer.startsWith("http://") ||
        			        answer.startsWith("https://")){
        			            if( answer.endsWith(".gif") ||
                			        answer.endsWith(".jpeg") ||
                			        answer.endsWith(".jpg") ||
                			        answer.endsWith(".png"))
            			        {
                			        result = "<img width='100%' src='"+answer+"' />";
                			    }else{
                			        result = answer+"&nbsp;&nbsp;<a class='waves-effect waves-light btn' href='"+answer+"' > <i class='material-icons'>language</i></a> ";
                			    }
    			        }else{
    			            result = answer;
    			        }
        			    generateBox(result);
        			}
        		});
        	};
        });

        // process a failed authentication
        wx.error(function (res) {
        	alert(res.errMsg);
        });

    </script>
	</body>
</html>
