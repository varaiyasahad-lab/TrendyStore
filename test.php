<?php
session_start();

if(!isset($_SESSION['test'])){
  $_SESSION['test'] = 1;
  echo "SESSION CREATED";
}else{
  $_SESSION['test']++;
  echo "SESSION VALUE = ".$_SESSION['test'];
}
