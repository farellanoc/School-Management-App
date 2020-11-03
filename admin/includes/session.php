<?php if (isset($_SESSION['admin'])) : ?>
        <?php
        $user = $_SESSION['admin']['username'];
        $name = $_SESSION['admin']['name'];
        $email = $_SESSION['admin']['email'];
        ?>
        <?php endif ?>