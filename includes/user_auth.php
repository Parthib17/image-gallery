<?php
require_once 'auth.php';
if (!isUserLoggedIn()) {
  header('Location: ../user/login.php');
  exit;
}
