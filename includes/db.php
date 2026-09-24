<?php

require "env.php";
function connexion(): mysqli{
    parse_env();
    $link = mysqli_connect(getenv('HOST'), getenv('SQL_USR'), getenv('SQL_PWD'))
    or die('Pb de connexion au serveur: ' . mysqli_connect_error());
    mysqli_select_db($link, 'Username') or die ('Pb de sélection BD : ' . mysqli_error($link)); //verif
return $link;};
