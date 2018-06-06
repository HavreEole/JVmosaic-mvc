<?php /* fichier cnam/nfa083/2017-cf1/inc/form-question-recup.inc.php 20180604-PBO */

/* RECUP QUESTION PSEUDO ==================================================== */
    if(isset($_GET['question_pseudo'])){
        $question_pseudo=(string) $_GET['question_pseudo'];
    } else {$question_pseudo=""; }

/* RECUP QUESTION TEXTE ================================================ */
    if(isset($_GET['question_texte'])){
        $question_texte=(string) $_GET['question_texte'];
    } else {$question_texte=""; }
