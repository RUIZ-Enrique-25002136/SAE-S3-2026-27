<?php

function parse_env () : void
{
    putenv(parse_ini_file('../.env'));
}