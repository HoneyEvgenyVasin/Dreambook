<!DOCTYPE html>
<html>
    <head>
        <?php
            include "head.php";
            $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
            parse_str($query, $args);
        ?>
        <title><?php echo $args['word'] ?></title>
    </head>
    <body class="body-wrapper">
        <script src="change_rating.js"></script>
        <?php include "header.php"; ?>
        <h2>К чему снится <?php echo $args['word']; ?>. Значения снов из разных сонников.</h2>
        <?php
            require_once("dbs_conn.php");
            require_once("h2_helper.php");

            $con_cfg = get_dbs_auth();
            $conn_str = "mysql:host=" . $con_cfg['host'] . ';dbname=' . $con_cfg['database'];
            try
            {
                $conn = new PDO($conn_str, $con_cfg['login'], $con_cfg['password']);

                $conn->query("SET NAMES 'utf8'");
                $conn->query("SET CHARACTER SET 'utf8'");
                $conn->query("SET SESSION collation_connection = 'utf8_general_ci'");

                $sql = "SELECT * FROM DreamBookTitle";
                $result = $conn->query($sql);
                $dream_books = [];

                //Сформируем список сонников 
                if($result != false)
                {
                    while($row = $result->fetch())
                    {
                        $dream_books[$row['id']] = $row['title'];
                    }
                }

                $word_id = $args['id'];

                $stmt = $conn->prepare("SELECT id, dreambook_id, record, likes, dislikes FROM DreamBookRecord  WHERE keyword_id = :word_id ORDER BY dreambook_id");
                $stmt->bindParam(':word_id', $word_id);


                echo '<div class="wrapper">';
                    if($stmt->execute() != false)
                    {
                            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach($results as $row)
                            {
                                $rid = $row['id'];
                                $like_count = $row['likes'];
                                $dislike_count = $row['dislikes'];
                                echo '<div class="card text-bg-primary mb-3">';
                                    getH2("card-header", $dream_books[$row['dreambook_id']]);
                                    echo '<div class="card-body">';
                                        echo '<div class="card-text">' . $row['record'] . '</div>';
                                        echo "<button class=\"btn btn-dark\" style=\"margin-top:10px\" onClick = \"like(event, $rid, this)\">Нравится $like_count</button>";
                                        echo "<button class=\"btn btn-danger\" style=\"margin-top:10px; margin-left:5px\" onClick = \"dislike(event, $rid, this)\">Не нравится $dislike_count</button>";
                                    echo '</div>';
                                echo '</div>';
                            }
                    }
                echo '</div>';
            }
            catch(PDOException $ex)
            {
                echo $ex->getMessage();
            }

            include "footer.php";
        ?>
    </body>
</html>