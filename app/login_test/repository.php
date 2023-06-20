<?php
//-----データベースへの接続-----------------------------------------------------------------------------------------------------------------------------------------------------------
include 'function/db.php';

session_start();// セッションを開始する
if (isset($_SESSION['login'])) {
    if (isset($_POST['logout'])) {
        unset($_SESSION['login']);
        header("Location: $script"); // ログアウト後にページをリダイレクト（これが無いとリロードすると何も表示されなくなる）
        exit();
    }
    echo 'UserID: ' . $_SESSION["login"]["user_id"] . '<br>'; // セッションIDとユーザーIDがリンクしているかを表示して確認
    echo '<p>ログイン中です</p>';
}


//SQL実行準備
function record_sql($db) {//$db（PDOインスタンス）は関数内のスコープ内からアクセスできないので、引数として値を渡す（基本的に関数内からは外部の変数にアクセスできない）
    $keyword = "";
    if(isset($_GET["keyword"])) {
        $keyword = htmlspecialchars($_GET["keyword"]);//inputから送られてきたデータをサニタイジング
    }
    $stmt = $db->prepare("SELECT item, MIN(favorite_id) as favorite_id FROM favorites WHERE item LIKE :keyword GROUP BY item");//itemデータ取り出し
    //MIN(favorite_id) AS favorite_id は favorite_id（数字）列の最小値（一番若い番）を選択し、その結果を favorite_id という名前で取得する。
    //MIN(favorite_id)のみだと、後の$result['favorite_id']が$result['min_favorite_id]という名前で保管され、ややこしくなるので、favorite_idという元の名前を新しく作る形になっている
    //GROUP BYはitemカラム全体をグループ化する。item カラムの値が同じレコードを1つのグループと見なす。そしてそのグループの中で favorite_id の最小値（つまり、MIN(favorite_id)）を取り出す。

    //keywordはプレースホルダでありSQL文を準備する際に具体的な値に置き換えられる予定（これはSQLインジェクションといった攻撃から保護するための一般的な方法で、プレースホルダを安全な方法で具体的な値に置き換える）
    $stmt->bindValue(':keyword', '%' . $keyword . '%');//bindValueメソッド内で%記号がキーワードの前後にあるのは、キーワードが文字列の任意の位置にある場合に一致するという条件を設けているため（%はワイルドカードと呼ぶ）
    //例えば「abc%」とすると、abcで始まる任意の文字列と一致、「%abc」とすると、abcで終わる任意の文字列と一致する。ワイルドカードの位置によって検索条件を柔軟に制御できる
    //bindValue()メソッドはSQL文の中のプレースホルダ（:keywordなど）に値をバインド（結びつけ）するためのもの。プリペアドステートメントと一緒に使われる。
    $stmt->execute();//sql実行
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);//fetchして全レコード抽出


    //selectしたデータをループで表示する
    $script = $_SERVER["SCRIPT_NAME"]; // このPHPファイルのパス
    echo '<form action="' . $script . '" method="POST">';//action属性に変数セットする時の書き方　"' . $script . '"
    foreach ($results as $result) {//DBから抽出したキーワードを含む全レコードに対してループを掛けてある分だけだす。foreach(変数, 代入される配列変数)で配列の要素を一つずつ順番に取り出す
        echo '<input type="checkbox" name="save_items[]" value="'.$result['item'].'">'; // 保存用に送るチェックボックスを表示。
        //name属性に[]を追加することで、複数選択が可能なチェックボックスを作ることができる（後にsave_itemsはforeachループで処理する）
        echo $result['item'] . '<br>';
        echo $result['favorite_id'] . '<br>';

    }
    // session_part_01($result);//ループ処理呼び出し
    echo '<input type="submit" name="save_button" value="選択したアイテムを保存">';// 削除ボタンを表示
    echo '</form>';
}
record_sql($db);

function record_save($db) {//この中ではスコープ外（record_sqlも含む）で定義された変数は使えないので、引数として渡す。$_POST は PHP のスーパーグローバル変数なのでOK
    // 削除ボタンが押された場合の処理
    if (isset($_POST['save_button']) && isset($_POST['save_items']) &&isset($_SESSION['login'])) {//値の入ったボタン、ラジオボタンの値がセット（クリック）、セッション情報がセットされていたら
        if (!isset($_POST['logout'])) {
            $user_id = $_SESSION["login"]["user_id"];
            foreach ($_POST['save_items'] as $item) {//foreach(変数, 代入される配列変数)でname="save_items[]に設定した配列の要素を一つずつ順番に取り出す
                //$itemはArray([0] => "item1", [1] => "item3")のように配列変数となっているので、insertに$itemをセットすれば一括保存ができるといった仕組みになっている
                $insert_query = "INSERT INTO favorites (user_id, item) VALUES ('$user_id', '$item')";// データの挿入クエリ
                $result = $db->exec($insert_query);// クエリを実行
                echo "{$item}を保存しました";
            }
        }else {//ログアウト情報がセットされていれば
            echo '<p>ログインするか新規登録をおこなってください。</p>';
            echo '<a href="register_form.php">新規登録する</a><br>';
            echo '<a href="login_form.php">ログインする</a>';
        }
    }
}
record_save($db);




?>