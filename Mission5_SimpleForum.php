<?php
    // データベース設定
    $dsn = 'データベース名';
    $user = "ユーザー名";
    $password = "パスワード";
    
    // 例外処理
    try {
        // ---ここからデータベース処理---
        // データベース接続
        $pdo = new PDO(
           $dsn,
           $user,
           $password,
           array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
           )
        );
        
        // テーブル作成
        $sql = "CREATE TABLE IF NOT EXISTS tb220380"
        ."("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        ."name char(32),"
        ."comment TEXT,"
        ."password char(32),"
        ."date DATETIME"
        .");";
        $stmt = $pdo -> query($sql);
        
        // テーブル削除
        // $sql = "DROP TABLE tb220380";
        // $stmt = $pdo -> query($sql);
        
        // テーブル表示
        $sql = "SHOW TABLES";
        $table = $pdo -> query($sql);
        foreach($table as $row){
        }
        
        // テーブル構成詳細表示
        $sql = "SHOW CREATE TABLE tb220380";
        $table = $pdo -> query($sql);
        foreach($table as $row){
        }
        // ---ここまでデータベース処理---
        
        // 変数
        $id = $_POST["id"];
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $date = new DateTime();
        $date = $date -> format("Y-m-d H:i:s");
        $createPassword = $_POST["createPassword"];
        
        $deleteNumber = $_POST["deleteNumber"];
        $deletePassword = $_POST["deletePassword"];
        
        $editNumber = $_POST["editNumber"];
        $editPassword = $_POST["editPassword"];
        
        // 初期表示処理
        $sql = "SELECT * FROM tb220380";
        $stmt = $pdo -> query($sql);
        $results = $stmt -> fetchAll();
        
        // 投稿処理
        if((isset($name) && $name !== "") &&
           (isset($comment) && $comment !== "") &&
           (isset($createPassword) && $createPassword !== "")
        ){
            if(isset($id) && $id !== ""){
                // 編集投稿データ保存処理
                $editData = "UPDATE tb220380 SET name = ?, comment = ?, date = ? WHERE id = ?";
                $sql = $pdo -> prepare($editData);
                $sql -> bindValue(1, $name, PDO::PARAM_STR);
                $sql -> bindValue(2, $comment, PDO::PARAM_STR);
                $sql -> bindValue(3, $date, PDO::PARAM_STR);
                $sql -> bindValue(4, (int)$id, PDO::PARAM_INT);
                $sql -> execute();
            } else{
                // 新規投稿データ保存処理
                $insertData = "INSERT INTO tb220380 (name, comment, password, date) 
                               VALUES (?, ?, ?, ?)";
                $sql = $pdo -> prepare($insertData);
                $sql -> bindValue(1, $name, PDO::PARAM_STR);
                $sql -> bindValue(2, $comment, PDO::PARAM_STR);
                $sql -> bindValue(3, $createPassword, PDO::PARAM_STR);
                $sql -> bindValue(4, $date, PDO::PARAM_STR);
                $sql -> execute();
            }
            // 投稿データ抽出処理
            $sql = "SELECT * FROM tb220380";
            $stmt = $pdo -> query($sql);
            $results = $stmt -> fetchAll();
        }
        
        // 削除処理
        if((isset($deleteNumber) && $deleteNumber !== "") &&
           (isset($deletePassword) && $deletePassword !== "")
        ){
            // データ削除処理
            $deleteData = "DELETE FROM tb220380 WHERE id = ?";
            $sql = $pdo -> prepare($deleteData);
            $sql -> bindValue(1, (int)$deleteNumber, PDO::PARAM_INT);
            $sql -> execute();
            // データ抽出処理
            $sql = "SELECT * FROM tb220380";
            $stmt = $pdo -> query($sql);
            $results = $stmt -> fetchAll();
        }
        
        // 編集処理
        if((isset($editNumber) && $editNumber !== "") &&
           (isset($editPassword) && $editPassword !== "")
        ){
            // 特定データ抽出処理
            $choiceData = "SELECT * FROM tb220380 WHERE id = ?";
            $sql = $pdo -> prepare($choiceData);
            $sql -> bindValue(1, (int)$editNumber, PDO::PARAM_INT);
            $sql -> execute();
            $result = $sql -> fetch();
        }
        
    } catch(PDOException $e){
        // エラー処理
        $error = $e -> getMessage();
    }
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <!--投稿フォーム-->
        <form action="" method="POST">
            名前：<input type="text" name="name" value="<?php echo $result['name'] ?>">
            コメント：<input type="text" name="comment" value="<?php echo $result['comment'] ?>">
            パスワード：<input type="password" name="createPassword">
            <input type="hidden" name="id" value="<?php echo $result['id'] ?>">
            <input type="submit" value="送信">
        </form>
        <!--削除フォーム-->
        <form action="" method="POST">
            削除対象番号：<input type="text" name="deleteNumber">
            パスワード：<input type="password" name="deletePassword">
            <input type="submit" value="削除">
        </form>
        <!--編集フォーム-->
        <form action="" method="POST">
            編集対象番号：<input type="text" name="editNumber">
            パスワード：<input type="password" name="editPassword">
            <input type="submit" value="編集">
        </form>
        <?php
            // データ表示処理
            foreach($results as $row){
                echo "No."
                    .$row["id"]." /"
                    ."名前："
                    .$row["name"]." /"
                    ."コメント："
                    .$row["comment"]." /"
                    ."投稿日時："
                    .$row["date"]
                    ."<br>";
            }
        ?>
    </body>
</html>