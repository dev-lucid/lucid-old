<?php

# The default config uses the lucid_session class, which simply provides an API for accessing properties of $_SESSION.
# If you make your own class that implements interface__lucid_session (defined in lucid/lib/php/lucid_session.php), then 
# all lucid code should work.
global $lucid;
$lucid->config['session'] = new lucid_session();
