<?php
/**
 * Created by @HackeaMesta
 */
session_start();
// DB Connection
include 'login.php';

if (isset($_SESSION["token"]) && isset($_SESSION["ytrfvbnjjhgfgb"]) ) {
  $l = new LoginApi();
  $l->setSecondTime();
} else {
  header("Location: index.php");
}
?>
