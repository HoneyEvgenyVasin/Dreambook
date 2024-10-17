<!doctype html>
<html>
    <head>
        <?php include "head.php"; ?>
        <title>Сонник. Толкования по ключевым словам</title>
    </head>
    <body class="body-wrapper">
        <?php include "header.php"; ?>
        <div>
            <p>Выберите ключевое слово из вашего сна:</p>
            <?php
                require_once("dbs_conn.php");
                require_once("link_helper.php");
                require_once("h2_helper.php");

                $con_cfg = get_dbs_auth();
                $conn_s = "mysql:host=" . $con_cfg['host'] . ";dbname=" . $con_cfg['database'];
                
                try{
                    $conn = new PDO($conn_s, $con_cfg['login'], $con_cfg['password']);

                    $conn->query("SET NAMES 'utf8'");
                    $conn->query("SET CHARACTER SET 'utf8'");
                    $conn->query("SET SESSION collation_connection = 'utf8_general_ci'");
                    
                    $sql = "SELECT id, name FROM DreamsKeyword WHERE id in (SELECT keyword_id FROM DreamBookRecord) ORDER BY name";
                    $result = $conn->query($sql);

                    if($result != false){
                        $letter = '\0';
                        $close_list = false;
                        
                        echo '<ul class="list-group">';
                        while($row = $result->fetch()){
                            if($letter != mb_substr(mb_strtoupper($row['name']), 0, 1))
                            {
                                $letter = mb_substr(mb_strtoupper($row['name']), 0, 1);
                                getH2("", $letter);
                            }
                            echo '<li>';
                            getLink("list-group-item list-group-item-action", $row['name'], "dreamsbykeyword.php?id=" . $row['id'] . '&word=' . $row['name']);
                            echo '</li>';
                        }
                        echo '</ul>';
                    }
                } catch(PDOException $ex){
                    echo $ex->getMessage();
                }

                include "footer.php";
            ?>
        </div>
    </body>
</html>