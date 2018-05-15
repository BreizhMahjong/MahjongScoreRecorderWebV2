<?php
require_once ("query_database_connection_config.php");

session_start ();

$database = null;
try {
	$database = new PDO (DB_TYPE . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USERNAME, DB_PASSWORD, array (
		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
	));
} catch (Exception $e) {
	die ('Erreur : ' . $e->getMessage ());
}
function executeQuery($query, $parameters) {
	global $database;
	$request = $database->prepare ($query);
	if ($parameters !== null) {
		$request->execute ($parameters);
	} else {
		$request->execute ();
	}
	return $request->fetchAll ();
}
function executeUpdate($query, $parameters) {
	global $database;
	$request = $database->prepare ($query);
	if ($parameters !== null) {
		return $request->execute ($parameters);
	} else {
		return $request->execute ();
	}
}
function beginTransaction() {
	global $database;
	$database->beginTransaction ();
}
function commit() {
	global $database;
	$database->commit ();
}
function rollBack() {
	global $database;
	$database->rollBack ();
}
?>