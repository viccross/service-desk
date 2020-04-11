# Service Desk - RACF

[![Documentation Status](https://readthedocs.org/projects/service-desk-racf/badge/?version=latest)](https://service-desk-racf.readthedocs.io/en/latest/?badge=latest)
[![Build Status](https://travis-ci.org/viccross/service-desk-racf.svg?branch=master)](https://travis-ci.org/viccross/service-desk-racf)

Application for support team who need to check, unlock and reset user passwords.

![Screenshot](ltb_sd_screenshot.jpg)

:exclamation: With great power comes great responsibility: this application allows to reset password of any user, you must protect it and allow access only to trusted users.

## IBM RACF Support
This version provides support for the LDAP server on IBM z/OS or z/VM.  When IBM Resource Access Control Facility (RACF) (also known as IBM Security Server) is used as the password store, the method of changing the password is different than most other LDAP servers.  Also, a valid but expired password would cause the "check password" function in the original to fail.

Password management configurations supported include:
- LDAP SDBM backend (native RACF)
- LDAP LDBM backend with Native Authentication (RACF Password/Passphrase)
- OpenLDAP with slapo-rwm rewriting LDAP Bind to SDBM (as described in ITSO Redbooks publication "Security for Linux on z/VM" (SG24-7728)
  - LDBM backend support coming

### RACF Management capability (under development)
A full-fledged RACF management facility is being developed.  Using the SDBM backend, RACF definitions other than passwords can be managed.  This will allow RACF to be administered in a non-command-line way.  It is intended to augment the function of the DirMaint-RACF connector.

:exclamation: The focus will be on z/VM RACF management in the first instance.  Over time, z/OS RACF may be included.

Planned resources for management include:
- User definitions and attributes (password/passphrases, SPECIAL, OPERATIONS, AUDITOR, etc)
- Group definitions, attributes, and connections (incl. GROUP SPECIAL)
- General resources (create/delete profiles, manage permissions, UACC, etc)
  - VMMDISK class 
  - SURROGAT class (logon-by)
  - VMCMD class
  - FACILITY class (e.g. ICHCONN)
  - General RACF options (SETROPTS)
  
