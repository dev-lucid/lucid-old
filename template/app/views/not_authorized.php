<?php
$html = bs::fieldset(['title'=>'Not Authorized'])->add(bs::paragraph('You are not authorized to perform that action. '));

lucid::set_nav('nav1','nav1');
lucid::set_nav('nav2','nav2');
lucid::replace('#body',$html->render());