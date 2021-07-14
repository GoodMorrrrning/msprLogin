<?php
//a db class for connecting to the database
  class Db {
    private static $instance = NULL;

    public static function getInstance() {
      if (!isset(self::$instance)) {
       
        self::$instance = mysqli_connect("localhost", "root", "", "test", 3310, 'utf8mb4');
      }
      return self::$instance;
    }
  }

?>