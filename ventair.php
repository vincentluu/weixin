<?php
/**
  * wechat php test
  */



//define your token
define("TOKEN", "vincentweixin");
$wechatObj = new wechatCallbackapiTest();
//$wechatObj->valid();

require_once './venta_menu.php';
$wechatObj->responseMsg();

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if(!$this->checkSignature()){
        	
        	exit;
        }
		echo $echoStr;
    }

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){
                
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $RX_TYPE = trim($postObj->MsgType);

                switch($RX_TYPE)
                {
                    case "text":
                        $resultStr = $this->handleText($postObj);
                        break;
                    case "event":
                        $resultStr = $this->handleEvent($postObj);
                        break;
                    default:
                        $resultStr = "Unknow msg type: ".$RX_TYPE;
                        break;
                }
                echo $resultStr;
        }else {
            echo "";
            exit;
        }
    }

    public function handleText($postObj)
    {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";  		
		if($keyword==1)
        {
            $msgType = "text";
            $contentStr = "欢迎关注VENTA德国原装进口空气净化器！";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        }
		else{
			$contentStr = "Unknow Event: ";
            $resultStr=responseText($object, $contentStr);
            echo $resultStr;
        }
    }

    public function handleEvent($object)
    {
        $contentStr = "";
        switch ($object->Event)
        {
            case "subscribe":
                $contentStr = "欢迎关注VENTA德国原装进口空气净化器！"."\n"."目前平台功能如下："."\n"."【1】 查询价格，请输入：数字1"."\n"."【2】 查询到货时间，请输入：数字2"."\n"."【3】 联系人方式，请输入：数字3"."\n"."更多内容，敬请期待...";
                break;
            default :
                $contentStr = "Unknow Event: ".$object->Event;
                break;
        }
        $resultStr = $this->responseText($object, $contentStr);
        return $resultStr;
    }
    
    public function responseText($object, $content, $flag=0)
    {
        $textTpl = "<xml>
				 <ToUserName><![CDATA[%s]]></ToUserName>
				 <FromUserName><![CDATA[%s]]></FromUserName>
				 <CreateTime>%s</CreateTime>
				 <MsgType><![CDATA[news]]></MsgType>
				 <ArticleCount>1</ArticleCount>
				 <Articles>
				 <item>
				 <Title><![CDATA[%s]]></Title>
				 <Description><![CDATA[%s]]></Description>
				 <PicUrl><![CDATA[%s]]></PicUrl>
				 <Url><![CDATA[%s]]></Url>
				 </item>
				 <item>
				 <Title><![CDATA[%s]]></Title> 
				 <Description><![CDATA[%s]]></Description>
				 <PicUrl><![CDATA[%s]]></PicUrl>
				 <Url><![CDATA[%s]]></Url>
				 </item>
				 </Articles>
				 </xml>";

				 $title="VENTA源自德国";
				 $description="VENTA源自德国";
				 $url="http://mp.weixin.qq.com/mp/appmsg/show?__biz=MzA5MDA2NzgxOA==&appmsgid=10000003&itemidx=1&sign=6f4f7113e8656fca2b44fdc92a3e75ab#wechat_redirect";
				 $picurl="http://mmsns.qpic.cn/mmsns/Qwx4PzvDv1ZzMY9ua9GpE362A9o33SyvDteyIlrSXOlmwa1ZQ0VAiag/0";

				 $title1="VENTA德国原装进口空气净化器";
				 $description1="VENTA德国原装进口空气净化器";
				 $picurl1="http://mmsns.qpic.cn/mmsns/Qwx4PzvDv1ZzMY9ua9GpE362A9o33SyvewQayvYRsN8nq9YRBzfThA/0";
				 $url1="http://mp.weixin.qq.com/mp/appmsg/show?__biz=MzA5MDA2NzgxOA==&appmsgid=10000003&itemidx=2&sign=5c023f7b728d1d12e7dd168d1063823d#wechat_redirect";

        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $title, $description, $picurl, $url, $title1, $description1, $picurl1, $url1);
        return $resultStr;
    }
		
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>