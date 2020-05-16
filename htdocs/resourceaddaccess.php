<?php
/*
 * Add an ACL entry to RACF
 */

$result = "";
$dn = "";
$racfid = "";
$accesslevel = "";

if (isset($_POST["dn"]) and $_POST["dn"]) {
    $dn = $_POST["dn"];
} else {
    $result = "dnrequired";
}

if (isset($_POST["racfid"]) and $_POST["racfid"]) {
    $racfid = $_POST["racfid"];
} else {
    $result = "racfidrequired";
}

if (isset($_POST["accesslevel"]) and $_POST["accesslevel"]) {
    $accesslevel = $_POST["accesslevel"];
}

if ($result === "") {

    require_once("../conf/config.inc.php");
    require_once("../lib/ldap.inc.php");

    # Connect to LDAP
    $ldap_connection = wp_ldap_connect($sdbm_ldap_url, $ldap_starttls, $ldap_binddn, $ldap_bindpw);

    $ldap = $ldap_connection[0];
    $result = $ldap_connection[1];

    if ($ldap) {
        $entry["racfAccessControl"] = "ID($racfid) $accesslevel";
        $modification = ldap_mod_add($ldap, $dn, $entry);
	$errno = ldap_errno($ldap);
        if ( $errno ) {
            $result = "addaccessfailed";
        } else {
            $result = "addaccessok";
        }
    }
}

header('Location: index.php?page=resource&dn='.$dn.'&resourceaddaccessresult='.$result);
