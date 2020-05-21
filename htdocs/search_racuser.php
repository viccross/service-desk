<?php
/*
 * Search RAQCF user entries in LDAP directory
 */ 

$result = "";
$search_query = "";
$nb_entries = 0;
$entries = array();
$size_limit_reached = false;
$filter_escape_chars = null;
if (!$search_use_substring_match) { $filter_escape_chars = "*"; }

if (isset($_POST["racfid"]) and $_POST["racfid"]) { $search_query = ldap_escape($_POST["racfid"], $filter_escape_chars, LDAP_ESCAPE_FILTER); }
 else { $result = "searchrequired"; }

if ($result === "") {

    require_once("../conf/config.inc.php");
    require_once("../lib/ldap.inc.php");

    # Connect to LDAP
    $ldap_connection = wp_ldap_connect($sdbm_ldap_url, $ldap_starttls, $ldap_binddn, $ldap_bindpw);

    $ldap = $ldap_connection[0];
    $result = $ldap_connection[1];

    if ($ldap) {

        # Search base
	$ldap_search_base = "profiletype=user,$sdbm_base";

        # Search filter
#        $ldap_filter = "(&".$ldap_user_filter."(|";
#        foreach ($search_attributes as $attr) {
#            $ldap_filter .= "($attr=";
#            if ($search_use_substring_match) { $ldap_filter .= "*"; }
#            $ldap_filter .= $search_query;
#            if ($search_use_substring_match) { $ldap_filter .= "*"; }
#            $ldap_filter .= ")";
#        }
        $ldap_filter = "(racfid=$search_query)";

        # Search attributes
        $attributes = array();
#        foreach( $search_result_items as $item ) {
#            $attributes[] = $attributes_map[$item]['attribute'];
#        }
#        $attributes[] = $attributes_map[$search_result_title]['attribute'];
#        $attributes[] = $attributes_map[$search_result_sortby]['attribute'];

        # Search for users
        $search = ldap_search($ldap, $ldap_search_base, $ldap_filter, $attributes, 0, $ldap_size_limit);

        $errno = ldap_errno($ldap);

        if ( $errno == 4) {
            $size_limit_reached = true;
        }
        if ( $errno != 0 and $errno !=4 ) {
            $result = "ldaperror";
            error_log("LDAP - Search error $errno  (".ldap_error($ldap).")");
        } else {

            # Sort entries
            if (isset($racuser_result_sortby)) {
                $sortby = $attributes_map[$racuser_result_sortby]['attribute'];
                ldap_sort($ldap, $search, $sortby);
            }

            # Get search results
            $nb_entries = ldap_count_entries($ldap, $search);

            if ($nb_entries === 0) {
                $result = "noentriesfound";
            } elseif ($nb_entries === 1) {
                $entries = ldap_get_entries($ldap, $search);
                $entry_dn = $entries[0]["dn"];
                $page = "racuser";
                include("racuser.php");
            } else {
                $entries = ldap_get_entries($ldap, $search);
                unset($entries["count"]);
                $smarty->assign("nb_entries", $nb_entries);
                $smarty->assign("size_limit_reached", $size_limit_reached);

# Run through the entries to populate the arrary
                foreach ( $entries as &$entry ) {
#                   echo "Searching for ".$entry["dn"]."\n";
                    $item = ldap_search($ldap, $entry["dn"], "(objectClass=*)", array("racfId","racfOwner","racfdefaultgroup","racflastaccess"), 0, $ldap_size_limit);
                    $errno = ldap_errno($ldap);
                    if ($errno !=0) { $result = "ldaperror"; return; }
                    $items = ldap_get_entries($ldap,$item);
                    $entry["racfid"]["count"] = 1;
                    $entry["racfowner"]["count"] = 1;
                    $entry["racfdefaultgroup"]["count"] = 1;
                    $entry["racflastaccess"]["count"] = 1;
                    $entry["racfid"][0] = $items[0]["racfid"][0];
                    $entry["racfowner"][0] = $items[0]["racfowner"][0];
                    $entry["racfdefaultgroup"][0] = $items[0]["racfdefaultgroup"][0];
                    $entry["racflastaccess"][0] = $items[0]["racflastaccess"][0];
#                echo "profilename = ".$entry["profilename"][0]."\n";
#                echo "racfowner = ".$entry["racfowner"][0]."\n";
                }
                unset($entry);
                $smarty->assign("entries", $entries);
                $columns = $racuser_result_items;
                if (! in_array($racuser_result_title, $columns)) array_unshift($columns, $racuser_result_title);
                $smarty->assign("listing_columns", $columns);
                $smarty->assign("listing_linkto",  isset($search_result_linkto) ? $search_result_linkto : array($racuser_result_title));
                $smarty->assign("listing_sortby",  array_search($search_result_sortby, $columns));
                $smarty->assign("show_undef", $search_result_show_undefined);
                $smarty->assign("truncate_value_after", $search_result_truncate_value_after);
            }
        }
    }
}

?>
