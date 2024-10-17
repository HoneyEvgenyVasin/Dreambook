<!DOCTYPE html>
<html>
    <head>
        <?php
            require_once("dbs_conn.php");

            include "head.php";
            include "header.php";
            
            $con_cfg = get_dbs_auth();

            $conn_str = "mysql:host=" . $con_cfg['host'] . ";dbname=" . $con_cfg['database'];
            try{
                $conn = new PDO($conn_str, $con_cfg['login'], $con_cfg['password']);

                $conn->query("SET NAMES 'utf8'");
                $conn->query("SET CHARACTER SET 'utf8'");
                $conn->query("SET SESSION collation_connection = 'utf8_general_ci'");

                parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $args);
                $rid = $args['id'];
                $keyword = $args['keyword'];
                $dr_name = $args['drname'];

                $stmt = $conn->prepare("SELECT record, likes, dislikes FROM DreamBookRecord WHERE id = :rid");
                $stmt->bindParam(':rid', $rid);
                $stmt->execute();

                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $row = $result[0];
                $record = $row['record'];
                $like_count = $row['likes'];
                $dislike_count = $row['dislikes'];
            } catch (PDOException $ex){
                echo $ex->getMessage();
            }
        ?>
        <title><?php echo $dr_name . ": " . $keyword ?></title>
    </head>
    <body class="body-wrapper">
        <script src="change_rating.js"></script>
        <h1><?php echo $dr_name . ". К чему снится " . $keyword ?></h2>
        <div class="card text-bg-primary mb-3" style="max-width:50rem">
            <div class="card-header">
                <?php echo $keyword; ?>
            </div>
            <div class="card-body">
                <div class="card-text">
                    <?php echo $record ?>
                </div>
                <div>
                    <?php 
                        echo "<button class=\"btn btn-dark\" style=\"margin-top:10px\" onClick = \"like(event, $rid, this)\">Нравится $like_count</button>";
                        echo "<button class=\"btn btn-danger\" style=\"margin-top:10px; margin-left:5px\" onClick = \"dislike(event, $rid, this)\">Не нравится $dislike_count</button>";
                    ?>
                </div>
            </div>
        </div>
        <?php include "footer.php"; ?>
    </body>
</html>