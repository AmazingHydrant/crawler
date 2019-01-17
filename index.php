<?php
require_once 'crawlerClass.php';
//header("Content-Type:text/html; charset=big5");

$crawler = new Crawler;
$page = 1019;
$i = 0;
$time = -microtime(true);
for (;$page<1092;$page++){ //1083 //1193
// &akiyear='.$year.'&akimonth='.$month.'
$page_url = 'http://cpami.tncg.gov.tw/serchList.jsp?&d-16544-p='.$page.'&regno=&B1=%B0%65%A5%58&ampakiname=&find=2&kind=A01&regyy=';
$links = $crawler->get_links($page_url,'1');
$i++;
}
$time += microtime(true);
echo 'get_links用了'.$time.'秒，共'.$i.'頁'.$crawler->lines.'筆資料<br/>';
$crawler->get_links_xpath('links.txt');
?>