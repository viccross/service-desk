# Service Desk - RACF

[![Documentation Status](https://readthedocs.org/projects/service-desk-racf/badge/?version=latest)](https://service-desk-racf.readthedocs.io/en/latest/?badge=latest)
[![Build Status](https://travis-ci.org/viccross/service-desk-racf.svg?branch=master)](https://travis-ci.org/viccross/service-desk-racf)

Application for support team who need to check, lock, unlock and reset user passwords.

![Screenshot](ltb_sd_screenshot.jpg)

:exclamation: With great power comes great responsibility: this application allows to reset password of any user, you must protect it and allow access only to trusted users.

## IBM RACF Support
This version provides support for the LDAP server on IBM z/OS or z/VM.  When IBM Resource Access Control Facility (RACF) (also known as IBM Security Server) is used as the password store, the method of changing the password is different than most other LDAP servers.  Also, a valid but expired password would cause the "check password" function in the original to fail.

Password management configurations supported include:
- LDAP SDBM backend (native RACF)
- LDAP LDBM backend with Native Authentication (RACF Password/Passphrase)
- OpenLDAP with slapo-rwm rewriting LDAP Bind to SDBM (as described in ITSO Redbooks publication "Security for Linux on z/VM" (SG24-7728)
  - LDBM backend support coming

### RACF Manager
This provides a graphical view of RACF resources.  Using the SDBM backend of the z/VM (or z/OS) LDAP Server, RACF definitions other than passwords can be managed.  This will allow RACF to be administered in a non-command-line way.  It is intended to augment the function of the DirMaint-RACF connector.

:exclamation: The focus will be on z/VM RACF management in the first instance.  Over time, z/OS RACF may be included.

Support for the following resource profiles is currently available:
- General resource profiles (e.g. VMMDISK, SURROGAT (logon-by), VMCMD, FACILITY (e.g. ICHCONN) )
  - view owner
  - view UACC
  - manage permissions
- User profiles
  - view owner
  - view default/connect groups
  - view attributes (SPECIAL, OPERATIONS, AUDITOR, etc)
  - view create date
  - view last access date/time
  - view logon days/times

Planned support includes:
- User profile updates (attributes, password/passphrase, group connections) 
- Group profiles
  - view details
  - manage
- General RACF options (SETROPTS)
  - manage password policy
  - RACLISTed classes
