<?php
$html = bs::container_fluid();
$html->add(bs::col(['size'=>[12,12,8,8]])->add(lucid::view('register_form')));
$html->add(bs::col(['size'=>[12,12,4,4]])->add(lucid::view('login_form')));

lucid::replace('#body',$html->render());
lucid::set_nav('nav1','nav1');
lucid::set_nav('nav2','nav2');
lucid::set_title(lucid::i18n('navigation-login'));
