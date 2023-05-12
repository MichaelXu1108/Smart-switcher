<?php
  include '/storage/ssd4/814/19467814/public_html/database.php';
  
  if (!empty($_POST)) {
    $id=$_POST["ID"];
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT * FROM statusled WHERE ID = ?';
    
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    Database::disconnect();
    
    echo $data['Stat'];
  }
?>