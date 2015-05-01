<h1><?=lucid::i18n('navigation-dashboard')?></h1>
<?php
lucid::replace('#body');
lucid::set_nav('nav1','nav1');
lucid::set_nav('nav2','nav2');
lucid::set_title(lucid::i18n('navigation-dashboard'));
?>