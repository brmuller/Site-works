<?php

  //DB connexion Info
  define('DB_SOURCE', 'mysql:host=localhost;dbname=workflow;charset=utf8');
  define('DB_USER', 'root');
  define('DB_PWD', '');


  //Default DateTimeZone
  date_default_timezone_set('Europe/Paris');

  //uploaded file max size
  define('MAX_FILE_SIZE', 1000000);
