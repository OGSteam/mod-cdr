<script src='./mod/cdr/js/tablesort.min.js'></script>

<!-- Include sort types you need -->
<script src='./mod/cdr/js/sorts/tablesort.number.min.js'></script>
<script src='./mod/cdr/js/sorts/tablesort.date.min.js'></script>

<?php
//tag active
$pub_galaxy = (isset($pub_galaxy)) ? $pub_galaxy : "all";

$activecdrgall = ($pub_galaxy == "all") ? "active" : "";
$activecdrg= array();
for ($i = 1; $i <= $server_config['num_of_galaxies']; $i++) {
    $activecdrg[$i] = ($pub_galaxy == $i) ? "active" : "";
}
?>

<div class="nav-page-menu">
    <div class="nav-page-menu-item  <?php echo $activecdrgall; ?> ">
        <a class="nav-page-menu-link" href="index.php?action=cdr&amp;galaxy=all">
            <?php echo $lang['menu_cdr']; ?>
        </a>
    </div>
    <?php for ($i = 1; $i <= $server_config['num_of_galaxies']; $i++) :?>
        <div class="nav-page-menu-item  <?php echo $activecdrg[$i]; ?> ">
        <a class="nav-page-menu-link" href="index.php?action=cdr&amp;galaxy=<?php echo $i;?>">
            G<?php echo $i; ?>
        </a>
    </div>
        <?php endfor;?>

</div>


<?php
// On récupère les technos pourle Fret
$user_empire = user_get_empire($user_data['user_id']);
$user_technology = $user_empire["technology"];
// todo utiliser formule de pitch
$fret_recycleur = 20000; 
if ($user_technology['Hyp'] !=0)
{
    $fret_recycleur = (20000 * (1 + 0.05 * $user_technology['Hyp']));
}
?>

<br>

<table id="trier" class="og-table og-medium-table">
    <thead>
        <tr>
            <th class='' data-sort-method='' style="text-align:center"><?php echo $lang['coord']; ?></th>
            <th class='' data-sort-method='' style="text-align:center"><?php echo $lang['nb_recy']; ?></th>
            <th class='' data-sort-method='' style="text-align:center"><?php echo $lang['total']; ?></th>
            <th class='' data-sort-method='' style="text-align:center"><?php echo $lang['metal']; ?></th>
            <th class='' data-sort-method='' style="text-align:center"><?php echo $lang['crystal']; ?></th>
            <th class='' data-sort-method='' style="text-align:center"><?php echo $lang['date']; ?></th>
        </tr>
    </thead>
    <tbody style="text-align:center">
        <?php
        if (isset($pub_galaxy) && $pub_galaxy != "") {
            if ($pub_galaxy == "all") {
                $galax = "";
            } else {
                $galax = " AND gal = '" . $pub_galaxy . "'";
            }
        } elseif (!empty($tc['galaxy'])) {
            $galax = " AND gal = '" . $tc['galaxy'] . "'";
        } else {
            $galax = "";
        }

        $sql = "SELECT * FROM " . T_CDR . " WHERE `total` > '" . $tc['taille'] . "'" . $galax . " ORDER BY " . $tc['tri1'] . " " . $tc['tri2'];
        $result = $db->sql_query($sql);
        $i = 1;

        while ($val = $db->sql_fetch_assoc($result)) {
            $cdr_total = $val['total'];
            $cdr_tot = number_format($val['total'], 0, '', ' ');
            $cdr_metal = $val['metal'];
            $cdr_met = number_format($val['metal'], 0, '', ' ');
            $cdr_cristal = $val['cristal'];
            $cdr_cri = number_format($val['cristal'], 0, '', ' ');
        ?>

            <tr>
                <td class=''><?php echo $val['gal'] . $val['coord']; ?></td>
                <td class=''><?php echo floor($val['total'] / $fret_recycleur); ?></td>
                <td class=''><?php
                                if ($val['total'] >= $tc['big']) {
                                    echo "<span style='text-color:\#" . $tc['big_color'] . "'>" . $cdr_tot . "</span>";
                                } elseif ($cdr_total >= $tc['medium']) {
                                    echo "<span style='color:\#" . $tc['medium_color'] . "'>" . $cdr_tot . "</span>";
                                } elseif ($cdr_total > $tc['small']) {
                                    echo "<span style='color:\#" . $tc['small_color'] . "'>" . $cdr_tot . "</span>";
                                } else {
                                    echo $cdr_tot;
                                }
                                ?></td>
                <td class=''><?php
                                if ($val['metal'] >= $tc['big']) {
                                    echo "<span style='color:#" . $tc['big_color'] . "'>" . $cdr_met . "</span>";
                                } elseif ($cdr_metal >= $tc['medium']) {
                                    echo "<span style='color:#" . $tc['medium_color'] . "'>" . $cdr_met . "</span>";
                                } elseif ($cdr_metal > $tc['small']) {
                                    echo "<span style='color:#" . $tc['small_color'] . "'>" . $cdr_met . "</span>";
                                } else {
                                    echo $cdr_met;
                                }
                                ?></td>
                <td class=''><?php
                                if ($val['cristal'] >= $tc['big']) {
                                    echo "<span style='color:#" . $tc['big_color'] . "'>" . $cdr_cri . "</span>";
                                } elseif ($cdr_cristal >= $tc['medium']) {
                                    echo "<span style='color:#" . $tc['medium_color'] . "'>" . $cdr_cri . "</span>";
                                } elseif ($cdr_cristal > $tc['small']) {
                                    echo "<span style='color:#" . $tc['small_color'] . "'>" . $cdr_cri . "</span>";
                                } else {
                                    echo $cdr_cri;
                                }

                                ?></td>
                <td class=''><?php echo date($lang['date_format'], $val['date']); ?></td>
            </tr>

        <?php
        }
        ?>
    </tbody>
</table>
<script>
    new Tablesort(document.getElementById('trier'));
</script>

<br>

<table class="og-table og-small-table" style="background: #2C2C2C">
<tbody>
    <tr>
        <td style="width: 2%; background: <?= "#" . $tc['small_color']; ?>;"></td>
        <td style="width: 10%;">&nbsp;<?= $lang['more_than'] . " " . number_format($tc['small'], 0, '', ' '); ?></td>
        <td style="width: 2%; background-color: <?= "#" . $tc['medium_color']; ?>"></td>
        <td style="width: 10%;">&nbsp;<?= $lang['more_than'] . " " . number_format($tc['medium'], 0, '', ' '); ?></td>
        <td style="width: 2%; background-color: <?= "#" . $tc['big_color']; ?>"></td>
        <td style="width: 10%;">&nbsp;<?= $lang['more_than'] . " " . number_format($tc['big'], 0, '', ' '); ?></td>
    </tr>
</tbody>
</table>