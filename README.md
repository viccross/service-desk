# Service Desk

[![Documentation Status](https://readthedocs.org/projects/service-desk/badge/?version=latest)](https://service-desk.readthedocs.io/en/latest/?badge=latest)
[![Build Status](https://travis-ci.org/ltb-project/service-desk.svg?branch=master)](https://travis-ci.org/ltb-project/service-desk)

Application for support team who need to check, unlock and reset user passwords.

![Screenshot](ltb_sd_screenshot.jpg)

:exclamation: With great power comes great responsibility: this application allows to reset password of any user, you must protect it and allow access only to trusted users.

This version provides support for the LDAP server on IBM z/OS or z/VM.  When IBM Resource Access Control Facility (RACF) (also known as IBM Security Server) is used as the password store, the method of changing the password is different than most other LDAP servers.  Also, a valid but expired password would cause the "check password" function in the original to fail.
