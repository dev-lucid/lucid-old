<?php
lucid::security()->require_logged_in();
lucid::security()->require_role('admin');
echo(lucid::view('organizations_grid')->render());

lucid::replace('#body');
lucid::set_nav('nav1','nav1');
lucid::set_nav('nav2','nav2');
lucid::set_title(lucid::i18n('navigation-organizations'));
