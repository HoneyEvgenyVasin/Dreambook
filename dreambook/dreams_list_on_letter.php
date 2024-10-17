<!DOCTYPE html>
<html>
    <?php
        $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        parse_str($query, $args);
    ?>
    <head>
        <?php include "head.php" ?>
        <title><?php echo "Сны на букву " . $args['letter'] ?></title>
    </head>
    <body class="body-wrapper">
        <?php include "header.php"; ?>
        <h2>Ниже предоставлен список снов на букву <?php echo $args['letter'] ?></h2>

        <?php
            require_once("dbs_conn.php");
            require_once("link_helper.php");

            $con_cfg = get_dbs_auth();
            $conn_s = "mysql:host=" . $con_cfg['host'] . ";dbname=" . $con_cfg['database'];
            
            try{
                $letter = $args['letter'];
                $conn = new PDO($conn_s, $con_cfg['login'], $con_cfg['password']);

                $conn->query("SET NAMES 'utf8'");
                $conn->query("SET CHARACTER SET 'utf8'");
                $conn->query("SET SESSION collation_connection = 'utf8_general_ci'");

                $sql = "SELECT id, name FROM DreamsKeyword WHERE id in (SELECT keyword_id FROM DreamBookRecord) AND LEFT(name, 1) = '$letter'";
                $result = $conn->query($sql);

                if($result != false){
                    echo '<ul class="list-group">';
                    while($row = $result->fetch()){
                        echo '<li>';
                        getLink("list-group-item list-group-item-action", $row['name'], "dreamsbykeyword.php?id=" . $row['id'] . '&word=' . $row['name']);
                        echo '</li>';
                    }
                    echo "</ul>";
                }
            } catch(PDOException $ex){
                echo $ex->getMessage();
            }

            include "footer.php";
        ?>
    </body>
</html>