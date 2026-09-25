<?php
declare(strict_types=1);
require __DIR__ . '/config/database.php';
$message='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $username=trim($_POST['username']??'');
  $name=trim($_POST['full_name']??'');
  $password=$_POST['password']??'';
  if($username===''||$name===''||strlen($password)<8){$message='Username and name are required. Password must contain at least 8 characters.';}
  else{
    $s=$pdo->prepare("SELECT COUNT(*) FROM users WHERE username=?");$s->execute([$username]);
    if((int)$s->fetchColumn()>0){$message='Username already exists.';}
    else{
      $s=$pdo->prepare("INSERT INTO users(username,password_hash,full_name,role) VALUES(?,?,?,'admin')");
      $s->execute([$username,password_hash($password,PASSWORD_DEFAULT),$name]);
      $message='Administrator created. Delete create_admin.php now, then login.';
    }
  }
}
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Create Administrator</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light"><div class="container mt-5"><div class="row justify-content-center"><div class="col-md-5"><div class="card shadow"><div class="card-body"><h3>Create Administrator</h3><?php if($message):?><div class="alert alert-info"><?=htmlspecialchars($message,ENT_QUOTES,'UTF-8')?></div><?php endif;?><form method="post"><input class="form-control mb-3" name="full_name" placeholder="Full name" required><input class="form-control mb-3" name="username" placeholder="Username" required><input class="form-control mb-3" type="password" name="password" placeholder="Password (minimum 8 characters)" required><button class="btn btn-primary w-100">Create Administrator</button></form></div></div></div></div></div></body></html>