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
<?php
lucid::replace('#nav1');
?>