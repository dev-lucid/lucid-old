<div class="panel panel-default">
    <div class="panel-heading">
        <strong><?=lucid::i18n('message-force_password_change_title')?></strong>
    </div>
    <div class="panel-body">
        <?=lucid::i18n('message-force_password_change_body')?>
        <form name="changePasswordForm" action="account/password_change" onsubmit="return lucid.form.submit(this);">
            <div class="form-group">
                <label for="name"><?=lucid::i18n('field-users-password')?></label>
                <input type="password" class="form-control" name="password" value="" />
            </div>
            <div class="form-group">
                <label for="name"><?=lucid::i18n('field-users-password-confirm')?></label>
                <input type="password" class="form-control" name="confirm_password" value="" />
            </div>
            <button type="submit" class="btn btn-primary"><?=lucid::i18n('action-change-password')?></button>
            <input type="hidden" name="user_id" value="<?=lucid::session()->user_id?>" />
            <input type="hidden" name="forced" value="yes" />
        </form>
    </div>
</div>
<?php
lucid::replace('#body');
lucid::controller('account')->change_password_ruleset()->send_javascript('changePasswordForm');

?>