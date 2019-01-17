<?php
ini_set("max_execution_time", "1800");
class Crawler{
	//dump html
	public $lines = 0;
	public function find_html($url){
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		//curl_setopt($curl,CURLOPT_HEADER,0);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		//curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		//curl_setopt($curl,CURLOPT_POSTFIELDS,$data); //设置post的参数
		//curl_setopt($curl,CURLOPT_HTTPHEADER,array('application/x-www-form-urlencoded;charset=utf-8','Content-length: '.strlen($data)));
		//curl_setopt($curl, CURLOPT_USERAGENT, "user-agent:Mozilla/5.0 (Windows NT 5.1; rv:24.0) Gecko/20100101 Firefox/24.0"); //解决错误：“未将对象引用设置到对象的实例。”
		$rtn = curl_exec($curl);
		return $rtn;
	}
	public function find_xpath($html,$xpath){
		/***********/
		// create document object model
		$dom = new DOMDocument();
		// load html into document object model
		@$dom->loadHTML($html);
		// create domxpath instance
		$domXPath = new DOMXPath($dom);
		// get all elements with a particular id and then loop through and print the href attribute
		$elements = $domXPath->query($xpath);
		return $elements;
		/***********/
	}
	//save xpath url to file
	public function get_links($url,$save=0){
		//$crawler = new Crawler;
		$html = $this->find_html($url);
		$xpath = '//table[@id="row"]//a/@href';
		$res = $this->find_xpath($html,$xpath);
		$links = array();
		foreach ($res as $e) {
			$links[] = 'http://cpami.tncg.gov.tw/'.$e->nodeValue;
			if ($save){
				file_put_contents('links.txt', 'http://cpami.tncg.gov.tw/'.$e->nodeValue.PHP_EOL, FILE_APPEND);
			}
			$this->lines += 1;
		}
		return $links;
	}
	//open file links per line
	public function get_links_xpath($links_file_path){
		$time = -microtime(true);
		$this->lines = 0;
		$fd = fopen($links_file_path, 'r');
		if($fd){
			while(!feof($fd)) {
				$link = str_replace(array("\n","\r"),"",fgets($fd));
				if (!$link){continue;}
				$html = $this->find_html($link);
				$xpath = '//table[@id="table2"]/tr[8]/td[4]//text()|//table[@id="table2"]/tr[7]/td[2]//text()|//table[@id="table2"]/tr[2]/td[2]//text()';
				$res = $this->find_xpath($html,$xpath);
				foreach ($res as $e) {
					file_put_contents('data_list.txt', str_replace(array("\n","\r"," "),"",$e->nodeValue), FILE_APPEND);
				}
				file_put_contents('data_list.txt', PHP_EOL, FILE_APPEND);
				$this->lines += 1;
			}
			$time += microtime(true);
			echo 'get_links_xpath用了'.$time.'秒，共'.$this->lines.'筆資料';
		}
		fclose($fd);
	}
}
?>