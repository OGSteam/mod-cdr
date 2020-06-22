<?php
/**
 * index.php
 * @package Mod Cdr
 * @version 1.70
 * @author Machine
 * @co-author Capi
 * @license http: //opensource.org/licenses/gpl-license.php GNU Public License
 */

if (!defined('IN_SPYOGAME')) die("Hacking attempt");

global $db, $table_prefix, $user_data;

define("TABLE_XTENSE_CALLBACKS", $table_prefix . "xtense_callbacks");
define("T_CDR", $table_prefix . "cdr");
define("M_CDR", $table_prefix . "mod_cdr");
list($version, $root) = $db->sql_fetch_row($db->sql_query("SELECT `version`, `root` FROM " . TABLE_MOD . " WHERE `action` = 'cdr'"));

require_once("mod/{$root}/lang/" . $ui_lang . "/lang_cdr.php");


$req = "SELECT COUNT(`id_user`) FROM " . M_CDR . " WHERE `id_user` =" . $user_data['user_id'];
$req1 = $db->sql_query($req);
list($id_exist) = $db->sql_fetch_row($req1);

require_once("views/page_header.php");

require_once ("mod/cdr/cdr_menu.php");

if (isset($pub_subaction)) {
    switch ($pub_subaction) {
        case "cdr" :

            $req = "SELECT count(`id_user`) FROM " . M_CDR . " WHERE `id_user` = " . $user_data['user_id'];
            $req1 = $db->sql_query($req);
            list($id_exist) = $db->sql_fetch_row($req1);

            if ($id_exist >= 1) {
                $req = "SELECT * FROM " . M_CDR . " WHERE `id_user`=" . $user_data['user_id'];
            }
            else {
                $req = "SELECT * FROM " . M_CDR . " WHERE `id_user` = 0";
            }
            $req1 = $db->sql_query($req);
            $tc = $db->sql_fetch_row($req1);

            // on supprime les vieux de plus de 2jours
            $vieux = (time() - (60 * 60 * 24) * $tc['retention']);
            // on fait du nettoyage au cas ou
            $query = "DELETE FROM `" . T_CDR . "` WHERE `date`<" . $vieux;
            $db->sql_query($query);

            ?>

            <br><br>

            <?php
            require_once("mod/cdr/cdr_list.php");
            break;
        case "option" :
            require_once("mod/cdr/cdr_options.php");
    }
}
require_once("./views/page_tail.php");

