<?php
/**
* update.php
* @package Mod Cdr
* @author Machine
* @co-author Capi
* @version 1.62
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @description Fichier de mise à jour du mod Cdr
*/

if (!defined('IN_SPYOGAME')) die("Hacking attempt");

global $db, $table_prefix;
define("TABLE_MOD_CDR", $table_prefix."mod_cdr");
define("TABLE_CDR", $table_prefix."cdr");

$mod_folder = "cdr";
$mod_name = "Champs de ruines";

update_mod($mod_folder, $mod_name);



