<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);


const HOST = 'localhost';
const BANCO ='projeto-ileva';
const USER = 'root';
const SENHA = '0000';

const DS =  DIRECTORY_SEPARATOR;
const DIR_APP = __DIR__;

const DIR_PROJETO = 'api';

if(file_exists('autoload.php')) {
    include 'autoload.php';
} else {
    echo "Erro ao incluir bootstrap";
    exit();
}