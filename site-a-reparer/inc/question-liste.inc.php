<h2>Liste des questions</h2>
<?php /* fichier cnam/nfa083/2017-cf1/inc/question-liste.inc.php 20180604-PBO */

/* RECUP question LISTE ===================================================== */ 
    $requete22="SELECT question_pseudo, question_texte "
             ."FROM question";
    $resultat22 = $con->query($requete22);

  while($donnees22 = $resultat22->fetch()) { ?>
      <p><?php echo $donnees22["question_pseudo"].' | '
                   .$donnees22["question_texte"]; ?>
      </p><?php                     // Retour à la ligne à chaque enregistrement
  } ?>
