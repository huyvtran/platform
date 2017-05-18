<?php

if (env('REQUEST_URI') == '/' && empty($_GET['test'])) {
    header('Location: /landing');
    exit();
}