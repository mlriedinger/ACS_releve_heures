<?php 

$title = "Paramètres";
$heading = "Paramètres";

?>

<?php ob_start(); ?>

    <form action="index.php?action=updateSettings" method="POST">

        <div class="row mt-5 mb-3 justify-content-md-center">
        
            <div class="col-sm-4 border rounded p-4">
                <p>Mode de saisie des relevés</p>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" value="1" id="dateTimeMgmtSwitch" name="dateTimeMgmtSwitch" <?= $_SESSION['dateTimeMgmt'] == 1 ? "checked" : "" ?>/>
                    <label class="form-check-label" for="dateTimeMgmtSwitch">Date et heure de début/fin</label>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" value="1" id="lengthMgmtSwitch" name="lengthMgmtSwitch" <?= $_SESSION['lengthMgmt'] == 1 ? "checked" : "" ?>/>
                    <label class="form-check-label" for="lengthMgmtSwitch">Durée</label>
                </div>
            </div>
        </div>   

        <div class="row mt-3 mb-3 justify-content-md-center">
            <div class="col-sm-4 border rounded p-4">
                <p>Autres options</p>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" value="1" id="tripMgmtSwitch" name="tripMgmtSwitch" <?= $_SESSION['tripMgmt'] == 1 ? "checked" : "" ?>/>
                    <label class="form-check-label" for="tripMgmtSwitch">Gestion du temps de trajet</label>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" value="1" id="breakMgmtSwitch" name="breakMgmtSwitch" <?= $_SESSION['breakMgmt'] == 1 ? "checked" : "" ?>/>
                    <label class="form-check-label" for="breakMgmtSwitch">Gestion du temps de pause</label>
                </div>
            </div>
        </div> 

        <div class="row mb-3 mt-5 justify-content-md-center">
                
            <div class="col-sm-4 mb-5 text-center">
                <input type="hidden" value="<?= $_SESSION['csrfToken'] ;?>" name="csrfToken"/>
                <input type="reset" value="Annuler" class="btn btn-light"/>
                <input type="submit" value="Enregistrer" class="btn btn-dark"/>
            </div>
            
        </div>
    </form>
       
<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>  
    <script>
        window.onload = function(){
            var menuItemSelector = "#navbarDropdown2";
            updateNavBarActiveAttribute(menuItemSelector);
        }
    </script>
    <script>
        let dateTimeSwitch = document.getElementById("dateTimeMgmtSwitch");
        let timeLengthSwitch = document.getElementById("lengthMgmtSwitch");

        dateTimeSwitch.addEventListener('click', () =>{
            dateTimeSwitch.checked && timeLengthSwitch.checked ? timeLengthSwitch.checked = !timeLengthSwitch.checked : "";
        });

        timeLengthSwitch.addEventListener('click', () =>{
            timeLengthSwitch.checked && dateTimeSwitch.checked ? dateTimeSwitch.checked = !dateTimeSwitch.checked : "";
        });
    </script>
<?php $script = ob_get_clean(); ?>

<?php require 'template.php'; ?> 
        