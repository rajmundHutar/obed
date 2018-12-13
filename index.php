<?php

//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
require_once dirname(__FILE__) . '/lib.php';
require_once dirname(__FILE__) . '/cfg.php';

$menus = collect_menus($sources, $cache_default_interval);

if (isset($_GET['json'])) {
	print_json($root, $menus);
	die;
}

header('content-type: text/html; charset=utf-8');
print_html_head($root);
?>

<style>
    h1, a {
        color: #69a120;
    }

    #panel-picker {
        color: #69a120;
    }

    #panel-picker-menu li:hover {
        color: #69a120;
    }

    #body {
        margin-bottom: 1em;
    }
</style>

<body>
	<div id="body">
<?php

/* ---------------------------------------------------------------------------*/

print_infobox();

/* ---------------------------------------------------------------------------*/

print_html($root, $menus);

/* ---------------------------------------------------------------------------*/

print_footer();

?>
	</div>
</body>
