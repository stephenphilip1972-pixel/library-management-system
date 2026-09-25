<?php
declare(strict_types=1);
$dsn='mysql:host=127.0.0.1;dbname=library_management;charset=utf8mb4';
try{$pdo=new PDO($dsn,'root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);}
catch(PDOException $e){http_response_code(500);exit('Database connection failed.');}