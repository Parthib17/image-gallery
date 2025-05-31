<?php
require_once 'auth.php';
if (!isAdminLoggedIn()) {
  header('Location: login.php');
  exit;
}
