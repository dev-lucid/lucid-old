<h1>Bootstrap starter template</h1>
<p class="lead">
  Use this document as a way to quickly start any new project.
  <br /> All you get is this text and a mostly barebones HTML document.
</p>
<?php
$users = Model::factory('users')->find_many();
foreach ($users as $user) {
    echo($user->email.' / '.$user->organization()->name.'<br />');
}

lucid::replace('#body');
?>