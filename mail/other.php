<?php
$siteTitle = get_option('site_title');
$from = get_option('administrator_email');
$body = __('Welcome!')
    . "</br> </br>"
    . __('Your account for the %s repository has been created. Please click the following link to activate your account:', $siteTitle) . "</br> </br>"
    . "---urlActivation--- </br> </br>"
    . __('%s Administrator', $siteTitle);