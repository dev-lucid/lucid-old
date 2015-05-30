<?php

# basic nav links. These are different if you're logged in
if(lucid::session()->user_id > 0)
{
  $nav1_links = [ 
    bs::anchor(['text'=>bs::icon('home').' Home',         'href'=>'#!view/dashboard']),
    bs::anchor(['text'=>bs::icon('user').' Users',        'href'=>'#!view/users']),
    bs::anchor(['text'=>bs::icon('briefcase').' Organizations','href'=>'#!view/organizations']),
  ];
}
else
{
  $nav1_links = [
    bs::anchor(['text'=>'Home',   'href'=>'#!view/index']),
    bs::anchor(['text'=>'About',  'href'=>'#!view/about']),
    bs::anchor(['text'=>'Contact','href'=>'#!view/contact']),
  ];
}

# add these links to the top nav/side nav
lucid::replace('#nav1a',bs::nav(['type'=>'navbar'])->add($nav1_links));


# determine the auth link
if(lucid::session()->user_id > 0)
{
  $auth_link = bs::anchor(['href'=>'#!authentication/logout','text'=>bs::icon('lock').' Logout']);
}
else
{
  $auth_link = bs::anchor(['href'=>'#!view/login','text'=>bs::icon('lock').' Login']);
}

# on the right side nav (visible-xs), the auth link goes on the bottom
$nav1_links[] = $auth_link;

# in a view larger than xs, show the auth link right aligned in the main navbar
lucid::replace('#nav1a',bs::nav(['type'=>'navbar'])->pull_right()->add($auth_link));  

# on the right side nav (visible-xs), the auth link goes on the bottom
lucid::replace('#nav1b',bs::list_group()->add_class('hidden visible-xs')->add($nav1_links));





/*
<ul class="nav navbar-nav">
    <li><a href="#!content/index"><span class="glyphicon glyphicon-home"> </span> <?=lucid::i18n('navigation-home')?></a></li>
    <li><a href="#!content/about"><span class="glyphicon glyphicon-comment"> </span> <?=lucid::i18n('navigation-about')?></a></li>
    <li><a href="#!content/contact"><span class="glyphicon glyphicon-envelope"> </span> <?=lucid::i18n('navigation-contact')?></a></li>
</ul>
<ul class="nav navbar-nav navbar-right">
    <?php if (lucid::security()->is_logged_in()) { ?>
    <li><a href="#!account/dashboard"><span class="glyphicon glyphicon-tasks"></span> <?=lucid::i18n('navigation-dashboard')?></a></li>
    <li><a href="#!account/edit_user|user_id|<?=lucid::session()->user_id?>"><span class="glyphicon glyphicon-cog"></span> <?=lucid::i18n('navigation-settings')?></a></li>
    <li><a href="#!authentication/logout"><span class="glyphicon glyphicon-lock"></span> <?=lucid::i18n('navigation-logout')?></a></li>
    <?php }else{ ?>
    <li><a href="#!content/login"><span class="glyphicon glyphicon-lock"></span> <?=lucid::i18n('navigation-login')?></a></li>
    <?php } ?>
    
</ul>
lucid::replace('#nav1');
*/