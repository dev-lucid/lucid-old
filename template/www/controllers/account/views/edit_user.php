<?php
$user = Model::factory('users')->find_one(lucid::request('user_id'));
$user->permission_check('select');
?>
<div class="container-fluid">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <strong><?=lucid::i18n('message-editing',$user->first_name.' '.$user->last_name)?></strong>
            </div>
            <div class="panel-body">
                <form name="userEditForm" action="account/user_save" onsubmit="return lucid.form.submit(this);">
                    <div class="form-group">
                        <label for="name"><?=lucid::i18n('field-users-email')?></label>
                        <input type="email" class="form-control" name="email" value="<?=$user->email?>" />
                    </div>
                    <div class="form-group">
                        <label for="name"><?=lucid::i18n('field-users-first_name')?></label>
                        <input type="text" class="form-control" name="first_name" value="<?=$user->first_name?>" />
                    </div>
                    <div class="form-group">
                        <label for="name"><?=lucid::i18n('field-users-last_name')?></label>
                        <input type="text" class="form-control" name="last_name" value="<?=$user->last_name?>" />
                    </div>
                    <button type="submit" class="btn btn-primary"><?=lucid::i18n('action-save')?></button>
                    <input type="hidden" name="user_id" value="<?=$user->user_id?>" />
                </form>
            </div>   
        </div>       
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="panel panel-primary">
            <?php if ($user->user_id === lucid::session()->user_id){ ?>
            <div class="panel-heading">
                <strong>Change Password</strong>
            </div>
            <div class="panel-body">
                <form name="changePasswordForm" action="account/password_change" onsubmit="return lucid.form.submit(this);">
                    <div class="form-group">
                        <label for="name"><?=lucid::i18n('field-users-password')?></label>
                        <input type="password" class="form-control" name="password" value="" />
                    </div>
                    <div class="form-group">
                        <label for="name"><?=lucid::i18n('field-users-password')?></label>
                        <input type="password" class="form-control" name="password" value="" />
                    </div>
                    <button type="submit" class="btn btn-primary"><?=lucid::i18n('action-change-password')?></button>
                    <input type="hidden" name="user_id" value="<?=$user->user_id?>" />
                </form>
            </div>   
            <?php } else { ?>
            <div class="panel-heading">
                <strong>Reset Password</strong>
            </div>
            <div class="panel-body">
                <form name="resetPasswordForm" action="account/password_reset" onsubmit="return lucid.form.submit(this);">
                    
                    <button type="submit" class="btn btn-primary"><?=lucid::i18n('action-reset-password')?></button>
                    <input type="hidden" name="user_id" value="<?=$user->user_id?>" />
                </form>
            </div>  
            <?php } ?>
            
        </div>
    </div>
</div>
<?php
lucid::replace('#body');
$this->user_save_ruleset()->send_javascript('userEditForm');
$this->change_password_ruleset()->send_javascript('changePasswordForm');

lucid::set_title(lucid::i18n('message-editing',$user->first_name.' '.$user->last_name));
lucid::set_nav('nav1','nav1');
lucid::set_nav('nav2','nav2');
?>