<?php

  //DB connexion Info
  define('DB_SOURCE', 'mysql:host=localhost;dbname=workflow;charset=utf8');
  define('DB_USER', 'root');
  define('DB_PWD', '');


  //Default DateTimeZone
  date_default_timezone_set('Europe/Paris');

  define('MAX_FILE_SIZE', 1000000); //max size of uploaded files (In Bytes)
  define('REPOSITORY_PATH','C:/wamp64/www/workflow/uploads'); //folder path of the files repository
