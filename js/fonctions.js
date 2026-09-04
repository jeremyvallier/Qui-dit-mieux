// Ce fichier contient les fonctions JavaScript utilisées
// avec le contrôleur ajax_refresh.php.


// Actualise les annonces du vendeur toutes les 10 secondes
function actualiserAnnonces() {

    // Récupère toutes les annonces du tableau de bord
    const annonces = document.querySelectorAll(".annonce-dashboard");

    annonces.forEach(function (annonce) {

        // Récupère l'identifiant de l'annonce
        const id = annonce.dataset.annonceId;

        // Demande les informations actualisées au serveur
        fetch("ajax_refresh.php?type=annonce&id=" + id)

            // Transforme la réponse en JSON
            .then(function (reponse) {
                return reponse.json();
            })

            // Met à jour les informations affichées
            .then(function (donnees) {

                // Arrête si le serveur retourne une erreur
                if (donnees.erreur) {
                    return;
                }

                // Récupère les éléments HTML à modifier
                const prix = document.getElementById(
                    "prix-annonce-" + id
                );

                const fin = document.getElementById(
                    "fin-annonce-" + id
                );

                const etat = document.getElementById(
                    "etat-annonce-" + id
                );

                // Met à jour le prix actuel
                prix.textContent = donnees.prix;

                // Vérifie si l'annonce est terminée
                if (donnees.terminee) {

                    // Affiche si l'annonce a été vendue ou non
                    if (donnees.vendu) {
                        etat.textContent = "Vendu";
                    } else {
                        etat.textContent = "Non vendu";
                    }

                    // Supprime la date de fin
                    fin.textContent = "";

                } else {

                    // Affiche la date de fin si l'annonce est encore active
                    fin.textContent = "Fin : " + donnees.date_fin;
                    etat.textContent = "";
                }
            });
    });
}


// Actualise les annonces du vendeur toutes les 10 secondes
setInterval(actualiserAnnonces, 10000);


// Actualise les annonces suivies ou sur lesquelles l'utilisateur a enchéri
// toutes les 2 secondes
function actualiserSuivis() {

    // Récupère toutes les annonces suivies du tableau de bord
    const annonces = document.querySelectorAll(".suivi-dashboard");

    annonces.forEach(function (annonce) {

        // Récupère l'identifiant de l'annonce
        const id = annonce.dataset.annonceId;

        // Demande les informations actualisées au serveur
        fetch("ajax_refresh.php?type=suivi&id=" + id)

            // Transforme la réponse en JSON
            .then(function (reponse) {
                return reponse.json();
            })

            // Met à jour les informations affichées
            .then(function (donnees) {

                // Arrête si le serveur retourne une erreur
                if (donnees.erreur) {
                    return;
                }

                // Récupère les éléments HTML à modifier
                const prix = document.getElementById(
                    "prix-suivi-" + id
                );

                const fin = document.getElementById(
                    "fin-suivi-" + id
                );

                const meilleur = document.getElementById(
                    "meilleur-suivi-" + id
                );

                // Met à jour le prix actuel
                prix.textContent = donnees.prix;

                // Indique si l'utilisateur est le meilleur enchérisseur
                if (donnees.meilleur_encherisseur) {

                    meilleur.textContent =
                        "Je suis le meilleur enchérisseur : OUI";

                    meilleur.style.color = "green";

                } else {

                    meilleur.textContent =
                        "Je suis le meilleur enchérisseur : NON";

                    meilleur.style.color = "red";
                }

                // Affiche l'état de l'enchère
                if (donnees.terminee) {
                    fin.textContent = "Enchère terminée";
                } else {
                    fin.textContent = "Fin : " + donnees.date_fin;
                }
            });
    });
}

// Actualise les annonces suivies toutes les 2 secondes
setInterval(actualiserSuivis, 2000);
// Actualise l'historique des enchères toutes les 10 secondes
function actualiserEncherisseur() {

    // Récupère le tableau de l'historique
    const historique = document.getElementById("historique-encheres");

    // Arrête la fonction si l'historique n'existe pas sur la page
    if (historique == null) {
        return;
    }

    // Récupère l'identifiant de l'annonce
    const id = historique.dataset.annonceId;

    // Demande l'historique actualisé au serveur
    fetch("ajax_refresh.php?type=encherisseur&id=" + id)

        // Transforme la réponse en JSON
        .then(function (reponse) {
            return reponse.json();
        })

        // Remplace l'historique affiché
        .then(function (donnees) {

            // Arrête si le serveur retourne une erreur
            if (donnees.erreur) {
                return;
            }

            historique.innerHTML = donnees.html;
        });
}


// Actualise l'historique toutes les 10 secondes
setInterval(actualiserEncherisseur, 10000);