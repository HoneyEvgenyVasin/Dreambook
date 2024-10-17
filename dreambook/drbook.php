<!DOCTYPE html>
<html>
    <head>
        <?php
            include "head.php";
            parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $args);
            $dr_name = $args['name'];
            echo "<title>ChilloutSounds.ru - " . $dr_name . "</title>"
        ?>
    </head>
    <body class="body-wrapper">
        <?php
            include "header.php";
        ?>
        
        <?php
            try {
                require_once("dbs_conn.php");
                require_once("link_helper.php");
                require_once("h2_helper.php");
                
                $con_cfg = get_dbs_auth();
                $data_source = "mysql:host=" . $con_cfg['host'] . ";dbname=" . $con_cfg['database'];
                $conn = new PDO($data_source, $con_cfg['login'], $con_cfg['password']);

                $conn->query("SET NAMES 'utf8'");
                $conn->query("SET CHARACTER SET 'utf8'");
                $conn->query("SET SESSION collation_connection = 'utf8_general_ci'");

                $sql = "SELECT id, image_url FROM DreamBookTitle WHERE title = '" . $dr_name . "'";
                $res = $conn->query($sql)->fetch();
                $dr_id = $res['id'];
                $img_url = $res['image_url'];

                echo "<div class = 'dr-header' align='center'>";
                echo "<h1>" . $dr_name . "</h1>";
                echo "<img class=\"dr-avatar\" src='$img_url' />";
                echo "</div>";
                echo '<h2>Выберите ключевое слово вашего сна:</h2>';
                if($dr_id != false){
                    $sql = "SELECT  DreamBookRecord.id as id, DreamsKeyword.name as name FROM DreamBookRecord, DreamsKeyword 
                        WHERE DreamsKeyword.id = DreamBookRecord.keyword_id AND DreamBookRecord.dreambook_id = " . $dr_id . " ORDER BY DreamsKeyword.name";
                    $result = $conn->query($sql);
                    
                    if($result != false){
                        $letter = "\0";
            
                        while($row = $result->fetch()){
                            $name = $row['name'];
                            $id = $row['id'];

                            if($letter != mb_substr(mb_strtoupper($name), 0, 1)){
                                $letter = mb_substr(mb_strtoupper($name), 0, 1);
                                getH2("", $letter);
                            }
                            getLink("btn btn-primary link-misc" , $name, "dream_page.php?book_id=" . $dr_id . "&id=" . $id . "&keyword=" . $name . "&drname=" . $dr_name);
                            echo "<br />";
                        }
                    }

                } else {
                    echo "dr_id = false";
                }
            } catch (PDOException $ex){
                echo $ex->getMessage();
            }

            include "footer.php";
        ?>
    </body>
</html>