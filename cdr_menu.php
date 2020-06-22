<?php
// Barre menu
if (!isset($pub_subaction)) {
    $subaction = "cdr";
    $pub_subaction = "cdr";
} else $subaction = $pub_subaction;

if ($subaction != "cdr") {
    $bouton1 = "\t\t\t<td class='c' style='text-align: center; width: 150pt' onclick=\"window.location = 'index.php?action=cdr&subaction=cdr';\">";
    $bouton1 .= "<a style='cursor:pointer'><span style=\"color: lime; \">" . $lang['menu_cdr'] . "</span></a>";
    $bouton1 .= "</td>";
} else {
    $bouton1 = "\t\t\t<th width='150'>";
    $bouton1 .= "<a>" . $lang['menu_cdr'] . "</a>";
    $bouton1 .= "</th>";
}

if ($subaction != "option") {
    $bouton2 = "\t\t\t<td class='c' style='text-align: center; width: 150pt' onclick=\"window.location = 'index.php?action=cdr&subaction=option';\">";
    $bouton2 .= "<a style='cursor:pointer'><span style=\"color: lime; \">" . $lang['menu_option'] . "</span></a>";
    $bouton2 .= "</td>";
} else {
    $bouton2 = "\t\t\t<th style='width:150pt'>";
    $bouton2 .= "<a>" . $lang['menu_option'] . "</a>";
    $bouton2 .= "</th>";
}
echo "<br><br><table>\n";
echo $bouton1 . $bouton2;
echo "</table>\n";