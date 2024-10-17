<footer class='dr-footer'>
    <h2>Chilloutsounds.ru - Сонник</h2>
    <div>
        <?php
            require_once("dbs_conn.php");
            $con_cfg = get_dbs_auth();
            $host = $con_cfg['host'];
            $dbs = $con_cfg['database'];
            $login = $con_cfg['login'];
            $pass = $con_cfg['password'];
            $records_count = 0;
            $con_str = "mysql:host=$host;dbname=$dbs";

            try
            {
                $con = new PDO($con_str, $login, $pass);
                $sql = "SELECT COUNT(*) AS 'count' FROM DreamBookRecord";
                $result = $con->query($sql);
                if($result != false)
                    $records_count = $result->fetch()['count'];
                
            }
            catch(PDOException $ex)
            {
                echo $ex->getMessage();
            }
        ?>
        <p>Дата: <?php echo date('d.m.y');?> Общее число толкований: <?php echo $records_count ?></p>
    </div>
</footer>