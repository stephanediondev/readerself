<?php
error_reporting(E_ALL);

$words = array('nullam', 'eget', 'dui', 'sed', 'mauris', 'lobortis', 'vestibulum', 'proin', 'condimentum', 'tincidunt', 'luctus', 'curabitur', 'ligula', 'dolor', 'hendrerit', 'sit', 'amet', 'iaculis', 'eu', 'dapibus', 'vel', 'magna', 'maecenas', 'et', 'risus', 'turpis', 'mauris', 'porttitor', 'malesuada', 'arcu');
$strings = array('Aenean facilisis vehicula diam', 'Aliquam nisl tortor venenatis', 'Curabitur sodales lorem vel ipsum', 'Donec mollis feugiat dolor quis', 'Duis sit amet nulla tortor', 'Elit sed gravida placerat ante', 'Etiam rhoncus scelerisque lorem', 'Fusce eget leo ut magna faucibus', 'Maecenas id nibh at purus pharetra', 'Orci a egestas sollicitudin quam', 'Morbi venenatis est sed turpis', 'Quisque ornare lorem adipiscing', 'Nam libero nisi lacinia congue', 'Duis imperdiet rhoncus fermentum', 'Sed sem ante, sodales non convallis');

session_start();

$mysql_connect = mysql_connect('localhost', 'reader', '8QczZynVRX9tnPDr');
mysql_select_db('reader', $mysql_connect);

$query = 'TRUNCATE TABLE feeds';
mysql_query($query, $mysql_connect);

$query = 'TRUNCATE TABLE subscriptions';
mysql_query($query, $mysql_connect);

$query = 'TRUNCATE TABLE items';
mysql_query($query, $mysql_connect);

$query = 'TRUNCATE TABLE tags';
mysql_query($query, $mysql_connect);

$query = 'TRUNCATE TABLE favorites';
mysql_query($query, $mysql_connect);

$query = 'TRUNCATE TABLE history';
mysql_query($query, $mysql_connect);

$total_feeds = 200;
//$total_subscriptions = 100;
$total_items = 100;
$total_tags = 10;
$rand_favorites = 3;
$rand_history = 3;

for($u=1;$u<=$total_tags;$u++) {
	$rand_key = array_rand($words, 1);
	$tag_title = $words[$rand_key];

	$query = 'INSERT INTO tags (mbr_id, tag_title, tag_datecreated) VALUES (
	\'1\', \''.$tag_title.'\', NOW())';
	mysql_query($query, $mysql_connect);

}

$items = 1;

for($u=1;$u<=$total_feeds;$u++) {
	$rand_key = array_rand($strings, 1);
	$fed_title = $strings[$rand_key];

	$query = 'INSERT INTO feeds (fed_title, fed_url, fed_link, fed_datecreated) VALUES (
	\''.$fed_title.'\', \''.$u.'\', \''.$u.'\', NOW())';
	mysql_query($query, $mysql_connect);

	$query = 'INSERT INTO subscriptions (mbr_id, fed_id, tag_id, sub_datecreated) VALUES (
	\'1\', \''.$u.'\', NULLIF(\''.rand(0, $total_tags).'\', \'0\'), NOW())';
	mysql_query($query, $mysql_connect);

	$max = rand(1, $total_items);
	for($i=1;$i<=$max;$i++) {
		$rand_key = array_rand($strings, 1);
		$itm_title = $strings[$rand_key];

		$rand_key = array_rand($words, 1);
		$itm_author = $words[$rand_key];

		$query = 'INSERT INTO items (fed_id, itm_title, itm_link, itm_author, itm_content, itm_date, itm_datecreated) VALUES (
		\''.$u.'\', \''.$itm_title.' '.$items.'\', \''.$u.'\', \''.$itm_author.'\', \'-\', NOW(), NOW())';
		mysql_query($query, $mysql_connect);
		$itm_id = mysql_insert_id($mysql_connect);
		$items++;

		$rand = rand(1, $rand_history);
		if($rand == 1) {
			$query = 'INSERT INTO history (mbr_id, itm_id, hst_datecreated) VALUES (
			\'1\', \''.$itm_id.'\', NOW())';
			mysql_query($query, $mysql_connect);
		}

		$rand = rand(1, $rand_favorites);
		if($rand == 1) {
			$query = 'INSERT INTO favorites (mbr_id, itm_id, fav_datecreated) VALUES (
			\'1\', \''.$itm_id.'\', NOW())';
			mysql_query($query, $mysql_connect);
		}

	}
}

//echo mysql_errno($mysql_connect).': '.mysql_error($mysql_connect).'<br />';

$query = 'OPTIMIZE TABLE feeds, subscriptions, items, tags, favorites, history';
mysql_query($query, $mysql_connect);

mysql_close($mysql_connect);
