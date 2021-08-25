<?php 

$title = "Nouveau relevé";
$heading = "Nouveau relevé";

?>

<?php ob_start(); ?>

    <?php include 'partials/recordForm.php'; ?>

<?php $content = ob_get_clean(); ?>

<?php 

$menuSelector = "#newRecordLink";
$iconSelector = "#newRecordIcon";
?>

<?php ob_start(); ?>
getWorksites(<?= $_SESSION['userId']?>);
getWorkCategories();
getWorkSubCategories();

<?php $additionalOnloadScript = ob_get_clean(); ?>

<?php require 'template.php'; ?>