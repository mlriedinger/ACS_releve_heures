<?php 

$title = "Nouveau relevé";
$heading = "Nouveau relevé";

?>

<?php ob_start(); ?>

    <?php include 'partials/recordForm.php'; ?>

<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>
    <script>
        window.onload = function() {
            getOptionsData('add', 'worksites', <?= $_SESSION['userId']?>);
            getWorkCategories();
            getWorkSubCategories(1);
            var menuItemSelector = "#navbarContent > ul.navbar-nav.me-auto.mb-2.mb-lg-0 > li:nth-child(2) > div > a";
            updateNavBarActiveAttribute(menuItemSelector);
        }
    </script>
<?php $script = ob_get_clean(); ?>

<?php require 'template.php'; ?>