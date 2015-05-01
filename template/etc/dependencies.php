<?php
global $dependencies;

$dependencies = [
    "lib/bootstrap"=>[
        "url"       =>"git@github.com:twbs/bootstrap.git",
        "local-url" =>["file:///opt/git-clones/bootstrap/"],
        "branch"    =>"v3.3.4",
        "revision"  =>null
    ],
    "lib/lucid"=>[
        "url"      =>"git@github.com:Dev-Lucid/lucid.git",
        "branch"   =>"1.0-bootstrap",
        "revision" =>null
    ],
    "lib/idiorm"=>[
        "url"       =>"git@github.com:j4mie/idiorm.git",
        "branch"    =>"v1.5.1",
        "revision"  =>null
    ],
    "lib/paris"=>[
        "url"       =>"git@github.com:j4mie/paris.git",
        "branch"    =>"v1.5.4",
        "revision"  =>null
    ],
    "lib/less.php"=>[
        "url"       =>"git@github.com:oyejorge/less.php.git",
        "branch"    =>"v1.7.0.3",
        "revision"  =>null
    ]
];

?>