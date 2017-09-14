<?php
/**
& author:david
* site:http://spetacular.github.io/
* weibo:http://weibo.com/mylxq
*/
$text = isset($_POST['text']) ? $_POST['text'] : '';
if(!$text){echo '[]';}

$obj = new MaxWordSegmentation();
$ret = $obj->run($text);
echo json_encode($ret);


class MaxWordSegmentation{

	private $dict = array();//保存字典的list

	function __construct(){
		$this->dict = require('./pub_ad_blocked_words.php');
	}
	
	

	//查看词是否在字典中
	function inDict($word){
		return array_key_exists($word,$this->dict);
	}

	//按照词典进行分词。正向最大匹配法
	function run($text,$encode = 'utf-8'){
		$minLen = 0;
		$maxLen = 0;
		//找出最长的单词长度及最短的单词长度
		foreach($this->dict as $key=>$value){
			$iLen = mb_strlen($key,$encode);
			if($minLen > $iLen || $minLen == 0 ){
				$minLen = $iLen;
			}

			if($maxLen < $iLen){
				$maxLen = $iLen;
			}
		}
		

		$sLen = mb_strlen($text, $encode);
		$result = array();
		for($start = 0;$start < $sLen;$start ++){	
			for($maxLoop = $maxLen;$maxLoop >= $minLen;$maxLoop --){
				$word = mb_substr ($text , $start, $maxLoop , $encode);
				//是否匹配成功
				if($this->inDict($word,$this->dict)){
					//添加到输出列表
					if(!in_array($word,$result)){
						$result[] = array($word,$this->dict[$word]);
					}
					break;
				}
			}
			
			
		}
		return $result;
	}
}


?>
