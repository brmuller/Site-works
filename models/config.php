<?php

  //DB connexion Info
  define('DB_SOURCE', 'mysql:host=localhost;dbname=workflow;charset=utf8');
  define('DB_USER', 'root');
  define('DB_PWD', '');


  //Default DateTimeZone
  date_default_timezone_set('Europe/Paris');

  define('MAX_FILE_SIZE', 1000000); //max size of uploaded files (In Bytes)
  define('REPOSITORY_PATH','C:/wamp64/www/workflow/uploads'); //folder path of the files repository

  //max rows display in views
  define('MAX_HISTORY_ROWS', 5); // max history events display in dashboard view
  define('MAX_TASK_ROWS', 8); // max tasks display in dashboard view
  define('MAX_TEAM_ROWS', 4); // max teams display in dashboard left menu view
  define('MAX_FLOW_ROWS', 4); // max teams display in dashboard left menu view
