<?php
declare(strict_types=1);
if(session_status()!==PHP_SESSION_ACTIVE)session_start();
function require_login():void{if(empty($_SESSION['user_id'])){header('Location: login.php');exit;}}
function require_role(array $roles):void{require_login();if(!in_array($_SESSION['role']??'', $roles,true)){http_response_code(403);exit('Access denied.');}}
function h(string $v):string{return htmlspecialchars($v,ENT_QUOTES,'UTF-8');}