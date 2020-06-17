<?php
/*
 * Display an entry
 */

$result = "";
$dn = "";
$entry = "";
$edit_link = "";
$checkpasswordresult= "";
$resetpasswordresult= "";
$accountunlockresult= "";
$posthookresult= "";

if (isset($_GET["dn"]) and $_GET["dn"]) {
    $dn = $_GET["dn"];
} elseif (isset($entry_dn)) {
    $dn = $entry_dn;
} else {
    $result = "dnrequired";
}

if (isset($_GET["checkpasswordresult"]) and $_GET["checkpasswordresult"]) {
    $checkpasswordresult = $_GET["checkpasswordresult"];
}

if (isset($_GET["resetpasswordresult"]) and $_GET["resetpasswordresult"]) {
    $resetpasswordresult = $_GET["resetpasswordresult"];
}

if (isset($_GET["accountunlockresult"]) and $_GET["accountunlockresult"]) {
    $accountunlockresult = $_GET["accountunlockresult"];
}

if (isset($_GET["posthookresult"]) and $_GET["posthookresult"]) {
    $posthookresult = $_GET["posthookresult"];
}

if ($result === "") {

    require_once("../conf/config.inc.php");
    require_once("../lib/ldap.inc.php");
    require_once("../lib/date.inc.php");

    # Connect to LDAP
    $ldap_connection = wp_ldap_connect($ldap_url, $ldap_starttls, $ldap_binddn, $ldap_bindpw);

    $ldap = $ldap_connection[0];
    $result = $ldap_connection[1];

    if ($ldap) {

        # Search attributes
        $attributes = array();
        $search_items = array_merge( $display_items, $display_password_items);
        foreach( $search_items as $item ) {
            $attributes[] = $attributes_map[$item]['attribute'];
        }
        $attributes[] = $attributes_map[$display_title]['attribute'];
        $attributes[] = "pwdPolicySubentry";

        # Search entry
        $search = ldap_read($ldap, $dn, $ldap_user_filter, $attributes);

        $errno = ldap_errno($ldap);

        if ( $errno ) {
            $result = "ldaperror";
            error_log("LDAP - Search error $errno  (".ldap_error($ldap).")");
        } else {
            $entry = ldap_get_entries($ldap, $search);
        }

        # Sort attributes values
        foreach ($entry[0] as $attr => $values) {
            if ( $values['count'] > 1 ) {
                asort($values);
            }
            if ( isset($values['count']) ) {
                unset($values['count']);
            }
            $entry[0][$attr] = $values;
        }

	if ($display_edit_link) {
		# Replace {dn} in URL
		$edit_link = str_replace("{dn}", urlencode($dn), $display_edit_link);
	}

        # Search user active password policy
        $pwdPolicy = "";
        if (isset($entry[0]['pwdpolicysubentry'][0])) {
            $pwdPolicy = $entry[0]['pwdpolicysubentry'][0];
	} elseif (isset($ldap_default_ppolicy)) {
            $pwdPolicy = $ldap_default_ppolicy;
        }

	$isLocked = false;
	$unlockDate = "";
	$isExpired = false;
	$ppolicy_entry = "";

        if ($pwdPolicy) {
            $search_ppolicy = ldap_read($ldap, $pwdPolicy, "(objectClass=pwdPolicy)", array('pwdMaxAge', 'pwdLockoutDuration'));

            if ( $errno ) {
                error_log("LDAP - PPolicy search error $errno  (".ldap_error($ldap).")");
            } else {
                $ppolicy_entry = ldap_get_entries($ldap, $search_ppolicy);
            }

            # Lock
            $pwdLockoutDuration = $ppolicy_entry[0]['pwdlockoutduration'][0];
            $pwdAccountLockedTime = $entry[0]['pwdaccountlockedtime'][0];

            if ( $pwdAccountLockedTime === "000001010000Z" ) {
                $isLocked = true;
            } else if (isset($pwdAccountLockedTime)) {
                if (isset($pwdLockoutDuration) and ($pwdLockoutDuration > 0)) {
                    $lockDate = ldapDate2phpDate($pwdAccountLockedTime);
                    $unlockDate = date_add( $lockDate, new DateInterval('PT'.$pwdLockoutDuration.'S'));
                    if ( time() <= $unlockDate->getTimestamp() ) {
                        $isLocked = true;
                    }
                } else {
                    $isLocked = true;
                }
            }

            # Expiration
            $pwdMaxAge = $ppolicy_entry[0]['pwdmaxage'][0];
            $pwdChangedTime = $entry[0]['pwdchangedtime'][0];

	    if (isset($pwdChangedTime) and isset($pwdMaxAge) and ($pwdMaxAge > 0)) {
                $changedDate = ldapDate2phpDate($pwdChangedTime);
                $expirationDate = date_add( $changedDate, new DateInterval('PT'.$pwdMaxAge.'S'));
                if ( time() >= $expirationDate->getTimestamp() ) {
                    $isExpired = true;
                }
	    }
        }

    }
}

$smarty->assign("entry", $entry[0]);
$smarty->assign("dn", $dn);

$smarty->assign("card_title", $display_title);
$smarty->assign("card_items", $display_items);
$smarty->assign("password_items", $display_password_items);
$smarty->assign("show_undef", $display_show_undefined);

$smarty->assign("isLocked", $isLocked);
$smarty->assign("unlockDate", $unlockDate);
$smarty->assign("isExpired", $isExpired);

$smarty->assign("edit_link", $edit_link);

$smarty->assign("checkpasswordresult", $checkpasswordresult);
$smarty->assign("resetpasswordresult", $resetpasswordresult);
$smarty->assign("accountunlockresult", $accountunlockresult);
$smarty->assign("posthookresult", $posthookresult);
?>
