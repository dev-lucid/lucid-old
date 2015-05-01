<?php

class lucid_controller_navigation extends lucid_controller 
{
    function check_state()
    {
        global $lucid;
        
        $nav_state = lucid::request('_nav_state',array());

        foreach($lucid->config['nav_state'] as $area=>$nav_view)
        {
            if(!isset($nav_state[$area]) or $nav_state[$area] != $nav_view)
            {
                lucid::log('navigation->check_state: nav area '.$area.' is not correct, should be '.$nav_view.'.');
                $this->$nav_view();
                $lucid->config['nav_state'][$area] = $nav_view;
            }
            else
            {
                lucid::log('navigation->check_state: nav area '.$area.' is correct, no need to change to '.$nav_view.'.');
            }
        }

        lucid::javascript('lucid.config.navState='.json_encode($lucid->config['nav_state']).';');
    }
}

?>