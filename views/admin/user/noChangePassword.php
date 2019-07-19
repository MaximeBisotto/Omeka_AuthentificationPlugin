<?php
$pageTitle = __('Changement impossible');
echo head(array(), "header");
?>
<h1><?php echo $pageTitle; ?></h1>

<p>
    Votre compte ne peut pas changer son mot de passe, vous passez par un système d'authentification externe.
</p>

<?php echo foot(array(), "footer"); ?>