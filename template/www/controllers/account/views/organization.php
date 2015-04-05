<?php
$org = Model::factory('organizations')->find_one($_REQUEST['org_id']);
$org->permission_check('select');
?>
<h1>Editing <?=$org->name?></h1>
<form name="orgEditForm" action="account/org_save" onsubmit="return lucid.form.submit(this);">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" name="name" value="<?=$org->name?>" />
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <input type="hidden" name="org_id" value="<?=$org->org_id?>" />
</form>
<?php
lucid::replace('#body');
?>