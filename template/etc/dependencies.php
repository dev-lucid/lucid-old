<?php
global $dependencies;

$dependencies = [
    "lib/bootstrap"=>[
        "url"       =>"git@github.com:twbs/bootstrap.git",
        "local-url" =>["file:///opt/git-clones/bootstrap/"],
        "branch"    =>"master",
        "revision"  =>null
    ],
    "lib/lucid"=>[
        "url"      =>"git@github.com:Dev-Lucid/lucid.git",
        "branch"   =>"1.0-bootstrap",
        "revision" =>null
    ],
    "lib/propel"=>[
        "url"       =>"git@github.com:propelorm/Propel.git",
        "local-url" =>["file:///opt/git-clones/propel/"],
        "branch"    =>"master",
        "revision"  =>null
    ],
    "lib/less.php"=>[
        "url"       =>"git@github.com:oyejorge/less.php.git",
        "local-url" =>["file:///opt/git-clones/less.php/"],
        "branch"    =>"master",
        "revision"  =>null
    ]
];

?>