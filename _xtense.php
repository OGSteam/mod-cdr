<?php
/**
 * _xtense.php
 * @package Mod Cdr
 * @author Machine
 * @co-author Capi
 * @version 1.60
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @description Fichier de liaison xtense avec le mod Cdr
 */

if (!defined('IN_SPYOGAME'))
    die("Hacking attempt");

global $db, $table_prefix, $user, $xtense_version;
$xtense_version = "2.2";
///define("TABLE_XTENSE_CALLBACKS", $table_prefix."xtense_callbacks");
define("TABLE_CDR", $table_prefix . "cdr");

// TEST XTENSE2

if (class_exists("Callback")) {
    /**
     * Class cdr_Callback
     */
    class cdr_Callback extends Callback
    {
        public $version = '2.3.9';

        /**
         * @param $system
         * @return int
         */
        public function cdr($system)
        {
            global $io;
            if (cdr($system))
                return Io::SUCCESS;
            else
                return Io::ERROR;
        }

        /**
         * @return array
         */
        public function getCallbacks()
        {
            return array(array('function' => 'cdr', 'type' => 'system'));
        }
    }
}
/**
 * @param $system
 * @return bool
 */
function cdr($system)
{
    global $db;
    // dump($system);
    // données a traiter
    // timestamp actuel
    $date = time();

    // On boucle dans la liste des résultats et on insert dans la DB
    for ($i = 0; $i < count($system['data']); $i++) {
        $rows = $i + 1;
        // galaxie
        $gal = $system['galaxy'];
        $sys = ':' . $system['system'] . ':' . $rows;

        $metal = isset($system['data'][$rows]['debris']['metal']) ? $system['data'][$rows]['debris']['metal'] : 0;
        $cristal = isset($system['data'][$rows]['debris']['cristal']) ? $system['data'][$rows]['debris']['cristal'] : 0;

        $total = $metal + $cristal;
        // suppression preventive (pas de doublons et effacement des cdr qui n'existent plus)
        // on supprime du param config
        $query = "DELETE FROM " . TABLE_CDR . " WHERE gal='" . $gal . "' AND coord='" .
            $sys . "'";
        $db->sql_query($query);

        // si un cdr est present
        if ($total !== 0 && $total > 5000) {
            //test
            $query = "INSERT INTO " . TABLE_CDR . " (date, total, metal, cristal, gal, coord)"
                  . " VALUES (" . $date . ", " . $total .", " . $metal . ", " . $cristal . ", '" . $gal . "', '" . $sys . "')";
            $db->sql_query($query);
        }
    }
    return true;
}
