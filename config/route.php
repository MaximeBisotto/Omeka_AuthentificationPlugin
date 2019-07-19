<?php

// ne pas mettre le users/login, mettre seulement le chemin pour les CAS

$route = array(
    array(
        'name' => 'user_cas',
        'route' => 'users/cas',
        'action' => 'cas',
        'label' => "Se connecter à l'Université d'Avignon",
        'domaine' => 'univ-avignon.fr',
        'etudiant' => 'alumni.univ-avignon.fr'
    ),
);