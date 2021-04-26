<?php 

$title = "Accueil";
$heading = "Chantiers du jour";

?>

<?php ob_start(); ?>

    <p style="text-align: justify">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget molestie eros. Aliquam sed enim eget leo lacinia pretium id sit amet mauris. Aenean sed pharetra massa. 
        Vestibulum nibh erat, accumsan vitae elit in, semper commodo ipsum. Phasellus rhoncus feugiat ligula, et ultrices tortor efficitur vel. 
        Etiam nisl arcu, scelerisque vitae nunc in, varius faucibus velit. Donec eleifend, quam sit amet lobortis scelerisque, felis nisi bibendum purus, in sodales mi ante quis libero.
    </p>

    <p style="text-align: justify">Aliquam gravida egestas eleifend. Curabitur vitae rhoncus nisl, in sodales augue. Vivamus vulputate enim quam, at fringilla lectus fermentum vitae. 
        Donec quis egestas mi, lacinia posuere risus. Proin eget ligula commodo augue fermentum aliquet eu ut orci. Nulla ornare ullamcorper risus a consequat. 
        Curabitur ullamcorper volutpat leo at facilisis. Integer eget neque erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. 
        Nullam consequat bibendum ante, non elementum arcu. Suspendisse ex magna, suscipit eget sollicitudin non, facilisis eget nibh. Ut malesuada nisi vitae blandit tincidunt. 
        Sed vehicula dictum fringilla. Maecenas rutrum augue vel magna consectetur, eu mattis lacus luctus. Vestibulum iaculis porttitor mauris nec gravida.
    </p>

    <p style="text-align: justify">Quisque lorem eros, dictum in mauris a, viverra varius neque. Phasellus justo dui, posuere nec justo vitae, accumsan lobortis turpis. 
        Suspendisse tincidunt consectetur nisl nec tincidunt. Proin vel viverra arcu. Quisque turpis arcu, posuere non maximus eget, suscipit eu lectus. 
        Vivamus ipsum nisl, pretium id fermentum eu, mattis non est. Quisque scelerisque gravida euismod. Nullam luctus mi nec diam lobortis blandit. 
        Quisque mattis molestie neque quis consectetur. Nullam dignissim quam sed congue posuere. Aliquam ante diam, congue non hendrerit non, tempor eu ante. 
        Vestibulum turpis purus, eleifend non lobortis nec, egestas nec tortor. Morbi vel sollicitudin urna, eget ullamcorper augue.
    </p>

    <p style="text-align: justify">Morbi interdum semper arcu luctus sodales. Mauris scelerisque suscipit neque. Cras nec ex placerat magna vulputate tincidunt sit amet vitae nunc. 
        Curabitur a dictum quam, sit amet fermentum ipsum. Quisque eget arcu eget nisl volutpat blandit a vitae nunc. Donec eu gravida nisi. 
        Sed vitae orci vitae ex sodales venenatis.
    </p>

    <p style="text-align: justify">Aenean arcu ipsum, scelerisque et venenatis in, dapibus et nisi. Mauris mattis erat lectus, vel tincidunt tortor tempor a. 
        In lacinia erat eget felis commodo volutpat. Suspendisse imperdiet ut urna a maximus. 
        Aenean pharetra, tortor eu rhoncus dapibus, metus nulla tempor nisi, id condimentum massa velit vitae ex. 
        Nam id egestas mi, semper tristique nunc. Aenean vitae ante eu nunc ullamcorper mattis. Fusce accumsan quis nisi vitae suscipit. 
        Quisque vel metus posuere, euismod mi eu, porttitor quam.
    </p>

<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>
    <script>
        window.onload = function() {
            getNumberOfRecordsToCheck('Check', 'unchecked');
    }
    </script>

<?php $script = ob_get_clean(); ?>

<?php require 'template.php'; ?>