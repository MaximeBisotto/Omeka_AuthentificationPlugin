<?php
if (is_admin_theme()) {
    header("Location: https://agorantic-corpus-dev.huma-num.fr/admin/users/login");
}
else {
    header("Location: https://agorantic-corpus-dev.huma-num.fr/users/login");
}