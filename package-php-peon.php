<?php
require __DIR__ . '/db.inc.php';
$GLOBALS["_CONFIG"]=json_decode( file_get_contents( __DIR__ . '/api.config.json' ), true );
require __DIR__ . '/lib/class.outputPeon.php';
require __DIR__ . '/lib/class.remotePeon.php';
require __DIR__ . '/lib/class.publicPeon.php';
