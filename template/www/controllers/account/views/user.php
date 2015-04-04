<?php
$user = Model::factory('users')->find_one($_REQUEST['user_id']);
if($user->org_id != $lucid->session->org_id and $lucid->session->role_name !== 'admin')
{
    lucid::log('User '.$lucid->session->user_id.' attempted to edit user '.$_REQUEST['user_id'].', which is not their organization.','security');
    lucid::redirect('content/not_authorized');
}
?>
<h1>Editing <?=$user->first_name?> <?=$user->last_name?></h1>
<?php
lucid::replace('#body');
?>