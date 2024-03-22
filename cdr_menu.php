<div class="ogspy-mod-header">
    <h2><?php echo $lang['title']; ?></h2>
</div>

<?php
// nouvelle barre menu
if (!isset($pub_subaction)) {
    $subaction = "cdr";
    $pub_subaction = "cdr";
} else $subaction = $pub_subaction;


//tag active
$activecdr = ($pub_subaction == "cdr") ? "active" : "";
$activeoption = ($pub_subaction == "option") ? "active" : "";

?>

<div class="nav-page-menu">
    <div class="nav-page-menu-item  <?php echo $activecdr; ?> ">
        <a class="nav-page-menu-link" href="index.php?action=cdr&amp;subaction=cdr">
            <?php echo $lang['menu_cdr']; ?>
        </a>
    </div>
    <div class="nav-page-menu-item  <?php echo $activeoption; ?> ">
        <a class="nav-page-menu-link" href="index.php?action=cdr&amp;subaction=option">
            <?php echo $lang['menu_option']; ?>
        </a>
    </div>
</div>
