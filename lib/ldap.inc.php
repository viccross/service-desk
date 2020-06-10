<?php
# LDAP Functions 

function wp_ldap_connect($ldap_url, $ldap_starttls, $ldap_binddn, $ldap_bindpw) {

    # Connect to LDAP
    $ldap = ldap_connect($ldap_url);
    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

    if ( $ldap_starttls && !ldap_start_tls($ldap) ) {
        error_log("LDAP - Unable to use StartTLS");
        return array(false, "ldaperror");
    }

    # Bind
    if ( isset($ldap_binddn) && isset($ldap_bindpw) ) {
        $bind = ldap_bind($ldap, $ldap_binddn, $ldap_bindpw);
    } else {
        $bind = ldap_bind($ldap);
    }

    if ( !$bind ) {
        $errno = ldap_errno($ldap);
        if ( $errno ) {
            error_log("LDAP - Bind error $errno  (".ldap_error($ldap).")");
        } else {
            error_log("LDAP - Bind error");
        }
        $errormsg = "ldaperror";
        ldap_get_option($ldap, LDAP_OPT_ERROR_STRING, $ldapmsg);
        if ( $errno == "49" ) {
            if ( strpos( $ldapmsg, "expired") ) {
                $errormsg = "passwordexpired";
            }
        }
        return array(false, $errormsg );
    }

    return array($ldap, false);
}

function wp_ldap_get_list($ldap, $ldap_base, $ldap_filter, $key, $value) {

    $return = array();

    if ($ldap) {

        # Search entry
        $search = ldap_search($ldap, $ldap_base, $ldap_filter, array($key, $value) );

        $errno = ldap_errno($ldap);

        if ( $errno ) {
            error_log("LDAP - Search error $errno  (".ldap_error($ldap).")");
        } else {
            $entries = ldap_get_entries($ldap, $search);
            for ($i=0; $i<$entries["count"]; $i++) {
                if(isset($entries[$i][$key][0])) {
                    $return[$entries[$i][$key][0]] = isset($entries[$i][$value][0]) ? $entries[$i][$value][0] : $entries[$i][$key][0];
                }
            }
        }
    }

    return $return;
}

function wp_ldap_check_auth($ldap_url, $ldap_starttls, $ldap_base, $ldap_attr) {

  $sessionTimeoutSecs = 1800;
  if ($ldap_attr == "" ) $ldap_attr = array("cn");

  if (!isset($_SESSION)) {
    if ( ! session_start() ) error_log("Session start failed");
  }

  if (!empty($_SESSION['lastactivity']) && $_SESSION['lastactivity'] > time() - $sessionTimeoutSecs && !isset($_GET['logout'])) {

    // Session is already authenticated
#    $ds = ldap_connect($ldapServer, $ldapPort);
#    $sr = ldap_search($ds,$ldapBaseDN,$ldapFilter,$ldapAttributes);
#    $result = ldap_get_entries($ds, $sr);
#
#    if ($result) {
#        $binddn = $result[0]['dn'];
#    } else {
#        header("Location: login.php?failure=1");
#    }
#
#    ldap_close ($ds);

    $ldap = ldap_connect($ldap_url);
    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    if ( $ldap_starttls && !ldap_start_tls($ldap) ) {
        error_log("LDAP - Unable to use StartTLS");
        $returnval = array("ldaptlserror", "login");
	goto endit;
    }

    if (ldap_bind($ldap, $_SESSION['binddn'], $_SESSION['password'])) {
      $_SESSION['lastactivity'] = time();
      $returnval = "success";
      goto endit;
    } else {
      unset($_SESSION['lastactivity'], $_SESSION['username'], $_SESSION['password'], $_SESSION['binddn']);
      $returnval = "ldapsessionerror";
      goto endit;
    }

  } else if (isset($_POST['username'], $_POST['password'])) {

    // Handle login requests
    $ldapFilter = "(&(objectClass=*)(uid=".$_POST['username']."))";
    $ldap = ldap_connect($ldap_url);
    if ( $ldap_starttls && !ldap_start_tls($ldap) ) {
        error_log("LDAP - Unable to use StartTLS");
        $returnval = "ldaptlserror";
	goto endit;
    }
    $sr = ldap_search($ldap,$ldap_base,$ldapFilter,$ldap_attr);
    $result = ldap_get_entries($ldap, $sr);

    if ($result) {
        $binddn = $result[0]['dn'];
    } else {
        $returnval = "ldapauth1error";
	goto endit;
    }
    ldap_close ($ldap);

    $ldap = ldap_connect($ldap_url);
    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    if ( $ldap_starttls && !ldap_start_tls($ldap) ) {
        error_log("LDAP - Unable to use StartTLS");
        $returnval = "ldaptlserror";
    }

    if (ldap_bind($ldap, $binddn, $_POST['password'])) {
      // Successful auth
      $_SESSION['lastactivity'] = time();
      $_SESSION['username'] = $_POST['username'];
      $_SESSION['password'] = $_POST['password'];
      $_SESSION['binddn'] = $binddn;
      $returnval = "success";
    } else {
      // Auth failed
      $returnval = "ldapauth2error";
    }
  } else {
    // Session has expired or a logout was requested
    unset($_SESSION['lastactivity'], $_SESSION['username'], $_SESSION['password'], $_SESSION['binddn']);
    $returnval = "ldapexperror";
  }
  endit:
  ldap_close ($ldap);
  return $returnval;
}

?>
