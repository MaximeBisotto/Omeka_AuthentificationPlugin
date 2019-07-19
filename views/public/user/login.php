<?php
queue_js_file('login');
$pageTitle = __('Log In');
echo head(array('bodyclass' => 'login', 'title' => $pageTitle), "header");
?>

    <link rel="stylesheet" type="text/css" href="/plugins/Authentification/views/style-login.css"/>

    <table>
        <tr>
            <th id="form">
                <h1><?php echo $pageTitle; ?></h1>

                <p id="login-links">
                    <span id="backtosite"><?php echo link_to_home_page(__('Go to Home Page')); ?></span>  |  <span id="forgotpassword"><?php echo link_to('users', 'forgot-password', __('Lost your password?')); ?></span>
                </p>

                <?php echo flash(); ?>

                <?php echo $this->form->setAction($this->url('users/login')); ?>
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

<?php echo foot(array(), "footer"); ?>