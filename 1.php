<?php
require_once 'crawlerClass.php';

function get_links(){
		$crawler = new Crawler;
		$links = array();
		$html = $crawler->find_html('http://cpami.tncg.gov.tw/serchList.jsp?&d-16544-p=1083&regno=&B1=%B0%65%A5%58&akiname=&find=2&kind=A01&regyy=');
		//header("Content-Type:text/html; charset=big5");
		//echo $html;
		$xpath = '//span[2]/a[9]//@href';
		$xpath = '//table[@id="row"]//a/@href';
		$res = $crawler->find_xpath($html,$xpath);
		foreach ($res as $e) {
			$links[] = 'http://cpami.tncg.gov.tw/'.$e->nodeValue;
		}
		return $links;
}
		
print_r(get_links());
?>
