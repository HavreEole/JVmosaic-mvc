<?php

    function getTagsInfos($numero, $compteDatas) {
        
        $pdo = new PDO(SERVEUR, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /*** récupérer tous les tags ***/
        $requete = $pdo->query('SELECT * FROM mz_tags ORDER BY nbUsages DESC, nom ASC');
        $resultat = $requete->fetchAll(PDO::FETCH_ASSOC);
        $compteDatas->set_allTags($resultat);
        $requete->closeCursor(); $requete=NULL; unset($resultat);

        /*** récupérer les tags de la personne ***/
        $requete = $pdo->prepare('  SELECT t.numero,t.nom FROM mz_tags t
                                INNER JOIN mz_depeindre d ON t.numero = d.numero_TAGS
                                WHERE d.numero_PERSONNE = :numero_p');
        $requete->execute(array('numero_p' => $numero));
        $resultat = $requete->fetchAll(PDO::FETCH_ASSOC);
        $resultat_tagsListNum = array();
        $resultat_tagsListNom = array();
        foreach ($resultat as $aTag) {
            array_push($resultat_tagsListNum,$aTag['numero']);
            array_push($resultat_tagsListNom,$aTag['nom']);
        }
        $compteDatas->set_tagsListNum($resultat_tagsListNum);
        $compteDatas->set_tagsListNom($resultat_tagsListNom);
        $requete->closeCursor(); $requete=NULL; unset($resultat);

        $pdo = NULL; // fin de connexion.
        
    }
    
    function addTags ($numero, $aTagNum) {
        
        $pdo = new PDO(SERVEUR, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        /*** vérifier que ce tag n'est pas déjà enregistré et qu'il n'y en a pas plus de 20 */
        $requete = $pdo->prepare('  SELECT numero_TAGS FROM mz_depeindre
                                    WHERE numero_PERSONNE = :numero_p');
        $requete->execute(array('numero_p' => $numero));
        $resultat = $requete->fetchAll(PDO::FETCH_COLUMN);
        $requete->closeCursor(); $requete=NULL;
        
        if ( count($resultat) < 10 && !in_array($aTagNum,$resultat)) {
            
            /*** si ok, lier cette personne à ce tag ***/
            $requete = $pdo->prepare('  INSERT INTO mz_depeindre(numero_PERSONNE,numero_TAGS) 
                                        VALUES (:numero_p,:numero_t)');
            $requete->execute(array('numero_p' => $numero,'numero_t' => $aTagNum));
            $requete->closeCursor(); $requete=NULL;
            
            /*** ajouter 1 nbUsages au tag ***/
            $requete = $pdo->prepare('  UPDATE mz_tags SET nbUsages=(nbUsages+1) WHERE numero=:numero_t');
            $requete->execute(array('numero_t' => $aTagNum));
            $requete->closeCursor(); $requete=NULL;
            
        }
        
        unset($resultat); $pdo = NULL; // fin de connexion.
        
    }
    
    function suprTags ($numero, $aTagNom) {
        
        $pdo = new PDO(SERVEUR, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        /*** vérifier que ce tag est bien lié à cette personne */
        $requete = $pdo->prepare('  SELECT d.numero FROM mz_depeindre d
                                    INNER JOIN mz_tags t ON t.numero = d.numero_TAGS
                                    WHERE numero_PERSONNE = :numero_p AND t.nom = :nom_t');
        $requete->execute(array('numero_p' => $numero,'nom_t' => $aTagNom));
        $resultat = $requete->fetchColumn();
        $requete->closeCursor(); $requete=NULL;
        
        if ( $resultat != false || $resultat === 0 ) {
            
            /*** si ok, supprimer cette liaison ***/
            $requete = $pdo->prepare('DELETE FROM mz_depeindre WHERE numero = :numero');
            $requete->execute(array('numero' => $resultat));
            $requete->closeCursor(); $requete=NULL;
            
            /*** retirer 1 de nbUsages du tag ***/
            $requete = $pdo->prepare('  UPDATE mz_tags SET nbUsages=(nbUsages-1) WHERE nom=:nom_t');
            $requete->execute(array('nom_t' => $aTagNom));
            $requete->closeCursor(); $requete=NULL;
            
        }
        
        unset($resultat); $pdo = NULL; // fin de connexion.
        
    }

    function createTag ($numero, $aTagNom) {
        
        $tagAlreadyExist = false;
        
        $pdo = new PDO(SERVEUR, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        /*** vérifier que ce tag n'existe pas déjà */
        $requete = $pdo->prepare('SELECT numero FROM mz_tags WHERE nom = :nom');
        $requete->execute(array('nom' => $aTagNom));
        $resultat = $requete->fetchColumn();
        $requete->closeCursor(); $requete=NULL;
        
        if ( $resultat === false ) {
            
            /*** si ok, ajouter le tag dans mz_tags ***/
            $requete = $pdo->prepare('INSERT INTO mz_tags(nom,nbUsages) VALUES (:nom,1)');
            $requete->execute(array('nom' => $aTagNom));
            $requete->closeCursor(); $requete=NULL;
            
            /*** récupérer son numéro ***/
            $requete = $pdo->prepare('SELECT numero FROM mz_tags WHERE nom = :nom');
            $requete->execute(array('nom' => $aTagNom));
            $resultat = $requete->fetchColumn();
            $requete->closeCursor(); $requete=NULL;
            
            /*** et lier le compte à ce tag ***/
            $requete = $pdo->prepare('  INSERT INTO mz_depeindre(numero_PERSONNE,numero_TAGS)
                                        VALUES (:numero_p,:numero_t)');
            $requete->execute(array('numero_p' => $numero,'numero_t' => $resultat));
            $requete->closeCursor(); $requete=NULL;
            
        } else {
            
            $tagAlreadyExist = true; // erreur
            
        }
        
            unset($resultat); $pdo = NULL; // fin de connexion.
            return $tagAlreadyExist;
        
    }

?>