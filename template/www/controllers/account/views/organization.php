<?php
if($_REQUEST['org_id'] != $lucid->session->org_id and $lucid->session->role_name !== 'admin')
{
    lucid::log('User '.$lucid->session->user_id.' attempted to edit organization '.$_REQUEST['org_id'].', which is not their organization.','security');
    lucid::redirect('content/not_authorized');
}
$org = Model::factory('organizations')->find_one($_REQUEST['org_id']);

?>
<h1>Editing <?=$org->name?></h1>
<?php

lucid::replace('#body');
?>