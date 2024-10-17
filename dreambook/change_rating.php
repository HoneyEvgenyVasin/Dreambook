<?php
    header('Content-Type: application/json');

    require_once("dbs_conn.php");
    
    $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
    parse_str($query, $args);

    $action = $args['action'];
    $rid = $args['rid'];
    $response = array();

    if($action == 'like' || $action == 'dislike')
    {
        $conn_cfg = get_dbs_auth();

        try
        {
            $con_cfg = get_dbs_auth();
            $conn_str = "mysql:host=" . $con_cfg['host'] . ';dbname=' . $con_cfg['database'];
            $conn = new PDO($conn_str, $con_cfg['login'], $con_cfg['password']);
            
            $conn->query("SET NAMES 'utf8'");
            $conn->query("SET CHARACTER SET 'utf8'");
            $conn->query("SET SESSION collation_connection = 'utf8_general_ci'");

            $column = 'likes';

            if($action == 'dislike')
                $column = 'dislikes';

            $sql = "UPDATE DreamBookRecord SET $column = $column + 1 WHERE id = $rid";
            $result = $conn->exec($sql);
            if($result == 0)
                $response['error'] = 'update failure';
            else
            {
                $result = $conn->query("SELECT likes, dislikes FROM DreamBookRecord WHERE id = $rid");
                if($result != false)
                {
                    $row = $result->fetch();
                    $likes = $row['likes'];
                    $dislikes = $row['dislikes'];
                    $response['likes'] = $likes;
                    $response['dislikes'] = $dislikes;
                }
            }
        }
        catch(PDOException $ex)
        {
            $msg = $ex->getMessage();
            echo "{\"error\" : \"$msg\"}";
        }
    
    }
    else
    {
        $response['error'] = 'bad action';
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>