<!DOCTYPE html>
<html lang="fr">

    <?php include('partials/head.php'); ?>

    <body>
        <div class="container">

            <div class="row justify-content-md-center">

                <div class="col-lg-3 text-center mt-5 mb-5">
                    <img src="public/img/logo.png" class="img-fluid" alt="Logo">
                </div>

            </div>
            
            <?php 
                if($errorCode == 1) {
                    ?>
                    <div class="row justify-content-md-center">

                        <div class="col-lg-3 text-center text-danger mb-3">
                            <p><?= $errorMessage ?></p>
                        </div>

                    </div>
                <?php    
                }
            ?>

            <div class="row justify-content-md-center">

                <div class="col-lg-3 text-center ">

                    <form action="index.php?action=login" method="POST">

                        <div class="form-group mt-3 mb-3">
                            <input class="form-control" type="text" name="login" placeholder="Identifiant" autocomplete="username" required/>
                        </div>

                        <div class="form-group mb-3">
                            <input class="form-control" type="password" name="password" placeholder="Mot de passe" autocomplete="current-password" required/>
                        </div>

                        <?php 
                            if($errorCode == 1045) {
                        ?>
                                <div>
                                    <small class="text-danger">Mauvais identifiant ou mot de passe</small>
                                </div>
                        <?php
                            }
                        ?>

                        <div class="form-group mt-4">
                        <input type="submit" value="Valider" class="btn btn-dark" />
                        </div>

                    </form>

                </div>

            </div>

        </div>
        <?php include('partials/footer.php'); ?>
    </body>
</html>