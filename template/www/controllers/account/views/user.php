<?php
$user = Model::factory('users')->find_one($_REQUEST['user_id']);
$user->permission_check('select');
?>
<h1>Editing <?=$user->first_name?> <?=$user->last_name?></h1>
<form name="userEditForm" action="account/user_save" onsubmit="return lucid.form.submit(this);">
    <div class="form-group">
        <label for="name">E-mail</label>
        <input type="email" class="form-control" name="email" value="<?=$user->email?>" />
    </div>
    <div class="form-group">
        <label for="name">First Name</label>
        <input type="text" class="form-control" name="first_name" value="<?=$user->first_name?>" />
    </div>
    <div class="form-group">
        <label for="name">Last Name</label>
        <input type="text" class="form-control" name="last_name" value="<?=$user->last_name?>" />
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <input type="hidden" name="user_id" value="<?=$user->user_id?>" />
</form>
<?php
lucid::replace('#body');
?>