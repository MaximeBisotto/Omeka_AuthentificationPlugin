<?php

$siteTitle = get_option('site_title');
$from = get_option('administrator_email');

$subject = __('Activate your account with the %s repository', $siteTitle);

$body = "Bienvenue! </br> </br>".
"Votre compte pour le site Plateforme Partage Corpus a été créé. Veuillez cliquer sur le lien suivant pour activer votre compte : </br> </br>".
"---urlActivation--- </br> </br>".
"Vous pouvez ensuite vous connecter par le CAS à la Plateforme en suivant le lien <strong> ---labelRoute--- </strong> en bas.";