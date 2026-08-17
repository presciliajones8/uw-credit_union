<?php
require_once __DIR__ . '/auth.php';

if (is_logged_in()) {
    redirect_to('dash.php');
}

include __DIR__ . '/index.html';
