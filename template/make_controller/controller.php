<?php

class lucid_controller_{table} extends lucid_controller
{
    public function save_ruleset()
    {
        return new lucid_ruleset(
            new lucid_rule('name','length',lucid::i18n('field-{table}-{edit_col}-validate-length'),['min'=>5,'max'=>20])
        );
    }

    public function save()
    {
        $id = lucid::request('{id_col}');
        if ($id == 0)
        {
            $obj = lucid::model('{table}')->create();
        }
        else
        {
            $obj = lucid::model('{table}')->find_one($id);
        }
        
        $obj->permission_check('update');
        $obj->{edit_col} = lucid::request('{edit_col}');
        $obj->save();
        lucid::log('User '.lucid::session()->user_id.' is saving {table}:'.$obj->id());
        lucid::message('info',lucid::i18n('message-changes_saved'));
        lucid::redirect(lucid::index_url('view/{table}'));
    }

    public function delete()
    {

        $id = lucid::request('{id_col}');
        lucid::log('User '.lucid::session()->user_id.' is deleting {table}:'.$id);
        $obj = lucid::model('{table}')->find_one($id);
        $obj->delete();
        lucid::message('info',lucid::i18n('message-changes_saved'));
        lucid::view('{table}_grid')->handle_return(true);
    }
}
