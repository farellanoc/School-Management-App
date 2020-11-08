<?php if (isset($_SESSION['teacher'])) : ?>
        <?php
        $user = $_SESSION['teacher']['username'];
        $name = $_SESSION['teacher']['name'];
        $email = $_SESSION['teacher']['email'];
        ?>
        <?php endif ?>