<?php
$org = Model::factory('organizations')->find_one(lucid::request('org_id'));
$org->permission_check('select');
?>
<div class="container-fluid">
    <h1 class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><?=lucid::i18n('message-editing',$org->name)?></h1>
</div>
<div class="container-fluid">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
        <div class="panel panel-primary">
            <div class="panel-heading">Organization Info</div>
            <div class="panel-body">
                <form name="orgEditForm" action="account/org_save" onsubmit="return lucid.form.submit(this);">
                    <div class="form-group">
                        <label for="name"><?=lucid::i18n('field-organizations-name')?></label>
                        <input type="text" class="form-control" name="name" value="<?=$org->name?>" />
                    </div>
                    <button type="submit" class="btn btn-primary"><?=lucid::i18n('action-save')?></button>
                    <input type="hidden" name="org_id" value="<?=$org->org_id?>" />
                </form>
                <br />&nbsp;<br />
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8">
        <?=$this->addresses_grid($org->org_id)->render()?>
    </div>
</div>
<?php
lucid::replace('#body');

lucid::log('looking for method org_save_ruleset in object class '.get_class($this));
$this->org_save_ruleset()->send_javascript('orgEditForm');

lucid::set_title(lucid::i18n('message-editing',$org->name));
lucid::set_nav('nav1','nav1');
lucid::set_nav('nav2','nav2');
?>