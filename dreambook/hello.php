<div>
    <p>Уважаемые посетители!
        Вы находитесь на сайте, который посвящён изучению и толкованию сновидений. 
        Сонник содержит подробную информацию о различных символах, которые могут привидеться вам во сне. 
        Для получения наиболее точного толкования постарайтесь вспомнить все детали сна. 
        Если вы хотите узнать значение какого-то конкретного символа, воспользуйтесь поиском по сайту или алфавитным указателем. 
        Также на нашем сайте вы можете найти статьи о том, как правильно толковать сны и как использовать полученную информацию для улучшения своей жизни. 
        Желаем приятного пользования нашим сонником!
    </p>
    <p>Выберите букву для вашего сна или воспользуйтесь поиском:</p>
    <div class="wrapper" align="center">
        <?php
            require_once("dbs_conn.php");
            require_once("link_helper.php");

            $con_cfg = get_dbs_auth();

            try{
                $conn = new PDO("mysql:host=" . $con_cfg['host'] . ";dbname=" . $con_cfg['database'], $con_cfg['login'], $con_cfg['password']);

                $conn->query("SET NAMES 'utf8'");
                $conn->query("SET CHARACTER SET 'utf8'");
                $conn->query("SET SESSION collation_connection = 'utf8_general_ci'");

                $sql = "SELECT DISTINCT LEFT(DreamsKeyword.name, 1) as letter FROM DreamBookRecord, DreamsKeyword WHERE DreamBookRecord.keyword_id = DreamsKeyword.id ORDER BY letter";
                $result = $conn->query($sql);
                if($result != false){
                    while($row = $result->fetch()){
                        getLink("btn btn-primary link-misc", mb_strtoupper($row['letter']), "dreams_list_on_letter.php?letter=" . $row['letter']);
                    }
                }
            } catch (PDOException $ex){
                echo $ex->getMessage();
            }
        ?>
        <form>
            <script src="searchloader.js">
            </script>
            <div class = "mb-3">
                <label for="search" class="form-label">Введите слово:</label>
                <input type="search" class="form-control" id="search">
                <button class="btn btn-primary" style="margin-top:10px" onClick = "do_search(event)">Поиск!</button>
            </div>
            <div id="search_reqults" class = "mb-3">
            </div>
        </form>
    </div>
</div>