<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>
        
        <title> <?= $title ?> </title>
        
        <!-- Chargement des fichiers Bootstrap en local -->
        <link rel="stylesheet" href="public/css/bootstrap/bootstrap.min.css"/>
        <!-- CDN pour charger Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous"/>
        
        <!-- CDN pour charger Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
        
        <!-- Toujours mettre la feuille de style en dernière position ! -->
        <link rel="stylesheet" href="public/css/style.css" />
    </head>

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