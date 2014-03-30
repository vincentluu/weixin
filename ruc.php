<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "vincentweixin");
$wechatObj = new wechatCallbackapiTest();
//$wechatObj->valid();
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
            $contentStr = "普通版279+20运费=299；纪念定制版299+20运费=319";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        }
		if($keyword==2)
        {
            $msgType = "text";
            $contentStr = "18610919483 张同学；13436483735 卢同学";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        }
		if($keyword==3)
        {
            $msgType = "text";
            $contentStr = "此次为第二批~大致在12月初到货";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        }
		else{
            $msgType = "text";
            $contentStr = "感谢您关注【人大纪念服】"."\n"."微信号：rucuniform"."\n"."无论你是刚入学的新生，还是快要毕业的学长学姐 在这四年中，你需要的不只是记忆，而是满载记忆的纪念服 当你穿着它的时候，你可以骄傲的告诉他们“我是人大的！” 不要等待，快来定制一件人大纪念服吧～快来加入RUC家族吧~"."\n"."目前平台功能如下："."\n"."【1】 查询价格，请输入：数字1"."\n"."【2】 查询联系人方式，请输入：数字2"."\n"."【3】 查询到货时间，请输入：数字3"."\n"."更多内容，敬请期待...";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        }
    }

    public function handleEvent($object)
    {
        $contentStr = "";
        switch ($object->Event)
        {
            case "subscribe":
                $contentStr = "感谢您关注【人大纪念服】"."\n"."微信号：rucuniform"."\n"."无论你是刚入学的新生，还是快要毕业的学长学姐 在这四年中，你需要的不只是记忆，而是满载记忆的纪念服 当你穿着它的时候，你可以骄傲的告诉他们“我是人大的！” 不要等待，快来定制一件人大纪念服吧～快来加入RUC家族吧~"."\n"."目前平台功能如下："."\n"."【1】 查询价格，请输入：数字1"."\n"."【2】 查询到货时间，请输入：数字2"."\n"."【3】 联系人方式，请输入：数字3"."\n"."更多内容，敬请期待...";
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
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>%d</FuncFlag>
                    </xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
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