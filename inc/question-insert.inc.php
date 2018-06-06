<?php /* fichier cnam/nfa083/2017-cf1/inc/question-insert.inc.php 20180604-PBO */

/* RECUP USER LISTE ============================================================ */
    if($question_pseudo){                                  /* uniquement si non vide */
        $requete21="INSERT INTO question(question_pseudo, question_texte) 
                   VALUES ('".$question_pseudo."','".$question_texte."')";
        /* DEBUG */ echo "requete21 : ".$requete21.'<br />';
        $resultat21 = $con->query($requete21);           /* Pour préparer et executer la requete */
    } ?>
