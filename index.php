<?
session_start();
include_once('utils.php');
Utils::readFileLines('.env');
include_once('db.php');

$db = new DB(DB_HOST, DB_NAME, DB_USER, DB_PASS);

echo 'Ready!';