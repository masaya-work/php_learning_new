<h2>配列テスト（foareachで全部出力）</h2>
<?php
$list = array(//配列を作る時はarrayで宣言する
	"Banana",
	"Orange",
	"Apple"
);
foreach( $list as $f ) { //foreach()は配列の時に使えるループ処理。変数$listの中にある配列の要素を$fに代入してループ
	echo "<p>$f</p>";//配列の数だけこの処理を行う
}
?>
<br><br><br>

<h2>配列テスト　変数名[]で中身を指定する場合(代入)①</h2>
<?php
$a = array();//配列を初期化した状態
$a[0] = "配列01";
$a[1] = "配列02";
$a[2] = "配列03";
$a[3] = "配列04";
$a[4] = "配列05";
echo $a[2];
?>
<br><br><br>

<h2>配列テスト　変数名[]で中身を指定する場合②</h2>
<?php
$b = array("配列01","配列02","配列03","配列04");
echo $b[3];
?>
<br><br><br>

<h2>配列テスト　0ではなく1から配列指定 array(1=>"1月");</h2>
<?php
$moon = array(1=>"1月", 2=>"2月", 3=>"3月", 4=>"4月", 5=>"5月", 6=>"6月", 7=>"7月", 8=>"8月", 9=>"9月" ,10=>"10月", 11=>"11月", 12=>"12月");
echo $moon[3]."<br>"; //表示結果は「3月」
?>
文字列と要素名を＝で設定する場合「連想配列」と呼ばれる。配列を[0]からではなく、[1]から指定したい場合にも使える
<br><br><br>

<h2>配列テスト　文字列と要素を逆にかくことも可能 array("1月"=>1);</h2>
<?php
$moon = array("1月"=>1, "2月"=>2, "3月"=>3); //表示結果は「2」
echo $moon["2月"]."<br>";
echo $moon["3月"]."<br>";
?>
<br><br><br>

<h2>配列テスト　連想配列を使ったforeach</h2>
<?php
	$list02 = array(//
	"バナナ"=>100,
	"オレンジ"=>200,
	"りんご"=>300
);
foreach( $list02 as $f02 => $price ) { //連想配列の入った$list02の中身を$f02に格納。連想配列の文字列(100など)を$priceとして変数にセット
	echo "<tr><th>$f02</th><td>{$price}円</td></tr><br><br>";//{$price}円は円の前にスペースがないので、{}で括る必要ありで変数展開可能
	echo "<tr><th>$f02</th><td>$price yen</td></tr><br><br>";//$priceとyenの間でスペースがあるので、{}で括る必要なしで変数展開可能
}
?>
<br><br><br>

<h2>配列テスト　関数array_unshift()を使って先頭に新たな配列を入れる</h2>
<pre><?php
$c = array(100,101,102);
array_unshift($c ,50, 51, 52);//既存の配列の先頭に新たな配列を加える関数
print_r($c);
?>
</pre>
<br><br><br>

<h2>配列テスト　関数array_push()を使って末尾に新たな配列を入れる</h2>
<pre>
<?php
$d = array(100,101,102);
array_push($d ,50, 51, 52);//既存の配列の末尾に新たな配列を加える関数
print_r($d); //print_r()は変数の中身を詳細に確認（配列の要素を出力）するために使用、開発やデバッグの際に使用される。echoはHTML文書に動的な要素を挿入する場合などに使用
?>
</pre>
<br><br><br>

<h2>配列テスト　関数を使って、先頭・末尾だけを取り出す</h2>
<?php
$e = array(100,101,102);
$e_sht = array_shift($e); //配列の先頭だけを取り出す関数
echo($e_sht);
echo "<br>";
$e_pop = array_pop($e); //配列の末尾だけを取り出す関数
echo $e_pop;
?>
<br><br><br>

<h2>配列テスト　配列内の要素を逆順に入れ替える</h2>
<pre>
<?php
$f = array(100,101,102);
$f_re = array_reverse($f);//要素を逆順に入れ替える関数
print_r ($f_re);
?>
</pre>