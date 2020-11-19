<?php
    define('MYSQL_USER','root');
    define('MYSQL_PASSWORD','1234');
    define('MYSQL_SERVER','localhost');
    define('MYSQL_DBNAME','programmerblog');
   
    $options = array(
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
    );
    
    $connection = new PDO(
        "mysql:host=".MYSQL_SERVER.";dbname=".MYSQL_DBNAME,MYSQL_USER,MYSQL_PASSWORD,$options
    );

?>