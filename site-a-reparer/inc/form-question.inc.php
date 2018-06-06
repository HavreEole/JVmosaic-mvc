<?php /* fichier cnam/nfa083/2017-cf1/inc/form-questoion.inc.php 20180604-PBO */

/* FORMULAIRE satisfaction  ===================================================== */ ?>
    <h2>Demande de complément d'information</h2>
    <form action="question.php" method="GET" name="form_satisfaction" >

      <p><strong><label for="question_pseudo" >Pseudo *</label></strong>      <!-- PSEUDO -->
          <input type="text" maxlength=20 required
              name="question_pseudo" id="question_pseudo"  /></p>

      <p><strong><label for="question_texte" >Votre question *</label></strong>      <!-- QUESTION -->
          <textarea required name="question_texte" id="question_texte" 
                cols='30' rows='4'></textarea>
      </p>

      <p><input type="submit" value="Envoyer" name="question_submit" /></p>
    </form>
