<?php
$html = bs::jumbotron(['title'=>'Hello World']);
$html->add(bs::paragraph('...'));
$html->add(bs::paragraph()->add(bs::button(['modifier'=>'primary','size'=>'lg','label'=>'Learn More'])));

lucid::replace('#body',$html->render());
lucid::set_nav('nav1','nav1');
lucid::set_nav('nav2','nav2');
lucid::set_title(lucid::i18n('navigation-home'));