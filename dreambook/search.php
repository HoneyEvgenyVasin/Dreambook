<?php
    header('Content-Type: application/json');

    require_once("dbs_conn.php");
    
    $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
    parse_str($query, $args);

    $conn_cfg = get_dbs_auth();

    try
    {
        $con_cfg = get_dbs_auth();
        $conn_str = "mysql:host=" . $con_cfg['host'] . ';dbname=' . $con_cfg['database'];
        $conn = new PDO($conn_str, $con_cfg['login'], $con_cfg['password']);
        
        $conn->query("SET NAMES 'utf8'");
        $conn->query("SET CHARACTER SET 'utf8'");
        $conn->query("SET SESSION collation_connection = 'utf8_general_ci'");

        $keywords = [];
        $dreambooks = [];

        //Получение ключевых слов
        $sql = "SELECT * FROM DreamsKeyword";
        $result = $conn->query($sql);

        if($result != false)
        {
            
            
            while($row = $result->fetch())
            {
                $keywords[$row['id']] = $row['name']; 
            }
        }

        //Получение сонников
        $sql = "SELECT * FROM DreamBookTitle";
        $result = $conn->query($sql);
   
        if($result != false)
        {
            while($row = $result->fetch())
            {
                $dreambooks[$row['id']] = $row['title'];
            }
        }

        //Поиск
        $search = $args['query'];
        $sql = "SELECT * FROM DreamBookRecord WHERE record LIKE '%$search%' ORDER BY record";
        $result = $conn->query($sql);
        if($result != false)
        {
            $response = array();

            while($row = $result->fetch())
            {
                $response[] = array(
                    "id"=>$row['id'],
                    "record"=>$row['record'],
                    "keyword_id"=>$row['keyword_id'],
                    "keyword"=>$keywords[$row['keyword_id']],
                    "dreambook_id"=>$row['dreambook_id'],
                    "dreambook"=>$dreambooks[$row['dreambook_id']]
                );
            }

            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    }
    catch(PDOException $ex)
    {
        $msg = $ex->getMessage();
        echo "{\"error\" : \"$msg\"}";
    }
    
?>