<?php
$links = ''; 
if (lucid::security()->is_logged_in())
{
    if (lucid::security()->is_role('admin'))
    {
        $links .= '<li><a href="#!account/dashboard">'.lucid::i18n('navigation-dashboard').'</a></li>';
        $links .= '<li><a href="#!account/users">'.lucid::i18n('navigation-users').'</a></li>';
        $links .= '<li><a href="#!account/organizations">'.lucid::i18n('navigation-organizations').'</a></li>';
    } 
    else
    {
        $links .= 'Logged in as non-admin';
    }
}
else
{
    $links .= 'Logged out';
}

lucid::replace('#nav2a', $links);
lucid::replace('#nav2b', $links);
?>