<html>
    <head>
        <title>Сонники</title>
        <?php include "head.php"; ?>
    </head>
    <body class="body-wrapper">
        <div class="wrapper">
            <?php include "header.php"; ?>
            <h2>Выберите нужный вам сонник:</h2>
            <?php
                require_once("dbs_conn.php");
                require_once("link_helper.php");
                require_once("h2_helper.php");
                
                try {
                    $con_cfg = get_dbs_auth();
                    $data_source = "mysql:host=" . $con_cfg['host'] . ";dbname=" . $con_cfg['database'];
                    $conn = new PDO($data_source, $con_cfg['login'], $con_cfg['password']);

                    $conn->query("SET NAMES 'utf8'");
                    $conn->query("SET CHARACTER SET 'utf8'");
                    $conn->query("SET SESSION collation_connection = 'utf8_general_ci'");

                    $sql = "SELECT * FROM DreamBookTitle ORDER BY title";
                    $result = $conn->query($sql);

                    if($result != false){
                        $letter = "\0";
            
                        while($row = $result->fetch()){
                            $name = $row['title'];
            
                            if($letter != mb_substr(mb_strtoupper($name), 0, 1)){
                                $letter = mb_substr(mb_strtoupper($name), 0, 1);
                                getH2("", $letter);
                            }
                            getLink("btn btn-primary link-misc" , $name, 'drbook.php?name=' . $name);
                            echo "<br/>";
                        }
                    }

                } catch (PDOException $e) {
                    echo "Ошибка загрузки данных: " . $e->getMessage(); 
                }

                include "footer.php";
            ?>
        </div>
    </body>
</html>