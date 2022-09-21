<?php

  $host = "localhost";
  $user = "root";
  $pass = "";
  $dbname = "bd_drpet";

  $conn = new PDO("mysql:host=$host;dbname=" . $dbname, $user, $pass);
?>