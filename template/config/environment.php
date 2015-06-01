<?php

# This file defines a constant named __STAGE__. Common values may be development, qa, production. 
# The example file simply checks if the app is being accessed on 127.0.0.1, and if so sets __STAGE__ to development.
# Otherwise, the value is production. 
#
# This file should be includeable without including any other libraries.

if ($_SERVER['REMOTE_ADDR'] === '127.0.0.1')
{
    define('__STAGE__','development');
}
else
{
    define('__STAGE__','production');
}
