<h1>About</h1>
<p class="lead">
  Use this document as a way to quickly start any new project.
  <br /> All you get is this text and a mostly barebones HTML document.
</p>
<?php

lucid::replace('#body');
lucid::set_nav('nav1','nav1');
lucid::set_nav('nav2','nav2');
lucid::set_title(lucid::i18n('navigation-about'));
?>