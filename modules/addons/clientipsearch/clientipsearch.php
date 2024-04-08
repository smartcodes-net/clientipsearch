<?php


if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function clientipsearch_config()
{
    $configarray = [
		"name" => "Client IP Search",
		"description" => "With this module you can search ip address who last logged same ip",
		"version" => "1.0.0",
		"author" => "<a href='https://www.smartcodes.net/' target='_blank'><img width='100px' src='../modules/addons/clientipsearch/logo.webp'></a>",
		"language" => "english",
	];
	
	return $configarray;
}


function clientipsearch_activate()
{


    return ["status" => "success", "description" => "Client IP Search module activated successfully."];

}


function clientipsearch_deactivate()
{

    return ["status" => "success", "description" => "Client IP Search module deactivated successfully."];

}




function clientipsearch_output($vars)
{
    global $aInt;
    $limit = 50;
    $page = $_REQUEST["page"];
    if (empty($page) || !is_numeric($page)) {
        $page = 0;
    } else {
        $page = $page;
    }
    $page = "" . $page . "";
    $records = $page * $limit;
    
    echo "<ul class=\"nav nav-tabs admin-tabs\" role=\"tablist\">\n    <li><a class=\"tab-top\" href=\"#tab2\" role=\"tab\" data-toggle=\"tab\" id=\"tabLink2\" data-tab-id=\"2\">";
echo $aInt->lang("global", "searchfilter");
echo "</a></li>\n</ul>\n<div class=\"tab-content admin-tabs\">\n    <div class=\"tab-pane\" id=\"tab2\">\n        <form action=\"\" method=\"post\">\n            <input type=\"hidden\" name=\"search\" value=\"1\">\n            <table class=\"form\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"3\">\n                <tr><td width=\"15%\" class=\"fieldlabel\">IP Address</td><td class=\"fieldarea\"><input type=\"text\" name=\"ip\" class=\"form-control input-300\" value=\"\" /></td><td class=\"fieldlabel\"></td><td></td></tr>\n            </table>\n            <div class=\"btn-container\"><input type=\"submit\" id=\"search-clients\" value=\"";
echo $aInt->lang("global", "search");
echo "\" class=\"button btn btn-default\"></div>\n        </form>\n\n    </div></div>\n<br />\n<script>\n    \$(document).ready(function () {\n        \$(\"a[href^='#tab']\").click(function () {\n            var tabID = \$(this).attr('href').substr(4);\n            var tabToHide = \$(\"#tab\" + tabID);\n            if (tabToHide.hasClass('active')) {\n                tabToHide.removeClass('active');\n            } else {\n                tabToHide.addClass('active')\n            }\n        });\n    });\n</script>\n";
    
    $result = \Illuminate\Database\Capsule\Manager::table("tblclients");
    if (isset($_REQUEST["ip"])) {
        if (isset($_REQUEST["ip"]) && $_REQUEST["ip"] != "") {
            $result->Where("ip", "LIKE", "%" . $_REQUEST["ip"] . "%");
            $result->where("ip", "LIKE", "%" . $_REQUEST["ip"] . "%");
        }
    }
    $result = $result->skip($records)->take($limit)->get();
    foreach ($result as $data) {
        $get_client = \Illuminate\Database\Capsule\Manager::table("tblclients")->where("id", $data->userid)->first();
        $tabledata[] = [
            "<a href='clientssummary.php?userid=" . $data->id . "'>" . $data->id . "</a>",
            "<a href='clientssummary.php?userid=" . $data->id . "'>" . $data->firstname . " " . $data->lastname . "</a>",
            $data->email,
        ];
    }
echo $aInt->sortableTable(["Client ID", "Client Name", "Client Email"], $tabledata);

    
}


