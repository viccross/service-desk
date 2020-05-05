<?php
/*
 * Search entries in LDAP directory
 */ 

$search_use_substring_match = false;
$search_result_title = "resource";
$search_result_items = array("resource", "owner");

$result = "";
$search_query = "";
$nb_entries = 0;
$entries = array();
$size_limit_reached = false;
$filter_escape_chars = null;
if (!$search_use_substring_match) { $filter_escape_chars = "*"; }

if (isset($_POST["racfclass"]) and $_POST["racfclass"]) { $racf_class = ldap_escape($_POST["racfclass"], $filter_escape_chars, LDAP_ESCAPE_FILTER); }
 else { $result = "searchrequired"; }
if (isset($_POST["resource"]) and $_POST["resource"]) { $search_query = ldap_escape($_POST["resource"], $filter_escape_chars, LDAP_ESCAPE_FILTER); }
 else { $result = "searchrequired"; }

# Will need something to select the class, and/or resource within the class
#$racf_class = "VMMDISK";
#$search_query = "MAINT";

if ($result === "") {

    require_once("../conf/config.inc.php");
    require_once("../lib/ldap.inc.php");

    # Connect to LDAP
    $ldap_connection = wp_ldap_connect($sdbm_ldap_url, $ldap_starttls, $ldap_binddn, $ldap_bindpw);

    $ldap = $ldap_connection[0];
    $result = $ldap_connection[1];

    if ($ldap) {

        # Search base
	$ldap_user_base = "profileType=".$racf_class.",".$sdbm_base;

        # Search filter
        $ldap_filter = "(profileName=";
#	if ($search_use_substring_match) { $ldap_filter .= "*"; }
	$ldap_filter .= $search_query;
#	if ($search_use_substring_match) { $ldap_filter .= "*"; }
        $ldap_filter .= ")";

        # Search attributes
        $attributes = array();
#        foreach( $search_result_items as $item ) {
#            $attributes[] = $attributes_map[$item]['attribute'];
#        }
#        $attributes[] = $attributes_map[$search_result_title]['attribute'];
#        $attributes[] = $attributes_map[$search_result_sortby]['attribute'];

        # Search for resources
        $search = ldap_search($ldap, $ldap_user_base, $ldap_filter, $attributes, 0, $ldap_size_limit);

        $errno = ldap_errno($ldap);

        if ( $errno == 4) {
            $size_limit_reached = true;
        }
        if ( $errno != 0 and $errno !=4 ) {
            $result = "ldaperror";
            error_log("LDAP - Search error $errno  (".ldap_error($ldap).")");
        } else {

            # Sort entries
            if (isset($search_result_sortby)) {
                $sortby = $attributes_map[$search_result_sortby]['attribute'];
                ldap_sort($ldap, $search, $sortby);
            }

            # Get search results
            $nb_entries = ldap_count_entries($ldap, $search);

            if ($nb_entries === 0) {
                $result = "noentriesfound";
            } elseif ($nb_entries === 1) {
                $entries = ldap_get_entries($ldap, $search);
                $entry_dn = $entries[0]["dn"];
                $page = "resource";
                include("resource.php");
            } else {
                $entries = ldap_get_entries($ldap, $search);
                unset($entries["count"]);
                $smarty->assign("nb_entries", $nb_entries);
                $smarty->assign("size_limit_reached", $size_limit_reached);

# Run through the entries to populate the arrary
                foreach ( $entries as &$entry ) {
#		    echo "Searching for ".$entry["dn"]."\n";
		    $item = ldap_search($ldap, $entry["dn"], "(objectClass=*)", array("profileName","racfOwner"), 0, $ldap_size_limit);
		    $errno = ldap_errno($ldap);
		    if ($errno !=0) { $result = "ldaperror"; return; }
		    $items = ldap_get_entries($ldap,$item);
		    $entry["profilename"]["count"] = 1;
		    $entry["racfowner"]["count"] = 1;
		    $entry["profilename"][0] = $items[0]["profilename"][0];
		    $entry["racfowner"][0] = $items[0]["racfowner"][0];
#                echo "profilename = ".$entry["profilename"][0]."\n";
#                echo "racfowner = ".$entry["racfowner"][0]."\n";
                }
		unset($entry);
                $smarty->assign("entries", $entries);
                $columns = $search_result_items;
                if (! in_array($search_result_title, $columns)) array_unshift($columns, $search_result_title);
                $smarty->assign("listing_columns", $columns);
                $smarty->assign("listing_linkto",  isset($search_result_linkto) ? $search_result_linkto : array($search_result_title));
                $smarty->assign("listing_sortby",  array_search($search_result_sortby, $columns));
                $smarty->assign("show_undef", $search_result_show_undefined);
                $smarty->assign("truncate_value_after", $search_result_truncate_value_after);
            }
        }
    }
}

?>
