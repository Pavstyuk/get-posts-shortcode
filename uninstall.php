<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

register_uninstall_hook(__FILE__, 'pgp_uninstall');

function pgp_uninstall()
{
    // do nothing
}
