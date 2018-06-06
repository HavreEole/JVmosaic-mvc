<?php /* fichier cnam/nfa083/2017-cf1/inc/entete.inc.php - 20180604-PBO */

  function entete($title,$actif) {
    session_start(); ?>
    <!DOCTYPE html><html lang="fr">
    <head><title><?php echo $title; ?></title>
          <meta charset='UTF-8' />
          <link href="style.css" rel="stylesheet" type="text/css" />
    </head>
    <body><h1><?php echo $title; ?></h1>
          <nav><a href="home.php" class="<?php if(!$actif){echo 'actif'; } ?>">
                  Présentation hébergeur</a>
               <a href="question.php" class="<?php if($actif){echo 'actif'; } ?>">
                  Question Hébergement</a>
          </nav>
    <?php
  } ?>
