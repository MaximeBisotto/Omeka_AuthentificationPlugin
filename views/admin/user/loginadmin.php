<?php

queue_js_file('login');
$pageTitle = __('Log In');
echo head(array('bodyclass' => 'login', 'title' => $pageTitle), "login-header");
?>

<!--    <style>-->
<!--        #email, #password {-->
<!--            width: 60%;-->
<!--        }-->
<!--    </style>-->

    <h1>Omeka</h1>

    <h2><?php echo link_to_home_page(option('site_title'), array("title" => __('Go to the public site'))); ?></h2>

    <link rel="stylesheet" type="text/css" href="/plugins/Authentification/views/style-login-admin.css"/>

    <table id="table">
        <tr>
            <th id="form" class="eight columns alpha offset-by-one">

                <?php echo flash(); ?>

<!--                <div class="eight columns alpha offset-by-one">-->
                    <?php echo $this->form->setAction($this->url('users/login')); ?>
<!--                </div>-->

                <p id="forgotpassword">
                    <?php echo link_to('users', 'forgot-password', __('(Lost your password?)')); ?>
                </p>
            </th>

            <th id="cas_link">
                <?php
                require dirname(__FILE__) . '/../../../config/route.php';
                foreach ($route as $item) {
                    $link=substr($item[route], 6);
                    echo "<a href=" . $link . ">" . $item['label'] . "</a> </br>";
                }
                ?>
            </th>
        </tr>
    </table>

<?php echo foot(array(), "login-footer"); ?>