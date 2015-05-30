<?php

$nav1_links = [
  bs::anchor(['text'=>'Home',   'href'=>'#!view/index']),
  bs::anchor(['text'=>'About',  'href'=>'#!view/about']),
  bs::anchor(['text'=>'Contact','href'=>'#!view/contact']),
  bs::anchor(['text'=>'Login','href'=>'#!view/login']),
];

lucid::replace('#nav1a',bs::nav(['type'=>'navbar'])->add($nav1_links));
lucid::replace('#nav1b',bs::list_group()->add_class('hidden visible-xs')->add($nav1_links));
