<?php 

$title = "Accueil";
$heading = "Accueil<br/>(à personnaliser)";

?>

<?php ob_start(); ?>
    <div class="row mb-5">
        <div class="col mt-5">
            <img src="<?= $_SESSION['imgFilePath']. "illustration.svg"?>" alt="Illustration d'organisation" height="300">
        </div>
        <div class="col mt-5">
            <p class="fs-3">Aliquam gravida egestas eleifend.</p>
            <p style="text-align: justify">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget molestie eros. Aliquam sed enim eget leo lacinia pretium id sit amet mauris. Aenean sed pharetra massa. 
                Vestibulum nibh erat, accumsan vitae elit in, semper commodo ipsum. Phasellus rhoncus feugiat ligula, et ultrices tortor efficitur vel. 
                Etiam nisl arcu, scelerisque vitae nunc in, varius faucibus velit. Donec eleifend, quam sit amet lobortis scelerisque, felis nisi bibendum purus, in sodales mi ante quis libero.
            </p>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col">
            <p class="fs-3">Donec eget molestie eros.</p>
            <p style="text-align: justify">Aliquam gravida egestas eleifend. Curabitur vitae rhoncus nisl, in sodales augue. Vivamus vulputate enim quam, at fringilla lectus fermentum vitae. 
                Donec quis egestas mi, lacinia posuere risus. Proin eget ligula commodo augue fermentum aliquet eu ut orci. Nulla ornare ullamcorper risus a consequat. 
                Curabitur ullamcorper volutpat leo at facilisis. Integer eget neque erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. 
                Nullam consequat bibendum ante, non elementum arcu. Suspendisse ex magna, suscipit eget sollicitudin non, facilisis eget nibh. Ut malesuada nisi vitae blandit tincidunt. 
                Sed vehicula dictum fringilla. Maecenas rutrum augue vel magna consectetur, eu mattis lacus luctus. Vestibulum iaculis porttitor mauris nec gravida.
            </p>
        </div>
    </div>
    
    <h2 class="display-6 text-center mb-3">Chantiers du jour</h2>

    <div class="row mb-5">
        <div class="col mt-3">
            <p class="fs-4 text-center" id="currentDate">JJ/MM/AAAA</p>
        </div>
    </div>

<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>
    <script>
        window.onload = function() {
            getNumberOfRecordsToCheck('Check', 'unchecked');
            getCurrentDate();
    }

        function getCurrentDate() {
            const today = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

            currentDate = document.getElementById("currentDate");
            currentDate.innerHTML = today.toLocaleDateString('fr-FR', options);
        }
    </script>

<?php $script = ob_get_clean(); ?>

<?php require 'template.php'; ?>