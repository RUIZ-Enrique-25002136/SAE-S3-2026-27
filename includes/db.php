<?php
function connexion(): mysqli{

    $link = mysqli_connect('localhost', 'admin', 'admin')
    or die('Pb de connexion au serveur: ' . mysqli_connect_error());
    mysqli_select_db($link, 'Username') or die ('Pb de sélection BD : ' . mysqli_error($link)); //verif
return $link;};