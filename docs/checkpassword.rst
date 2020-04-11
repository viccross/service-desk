Check password
==============

This feature allows to enter a password and check authentication.

.. warning:: the authentification can fail even if the password is correct.
             This is currently the case if account is locked or password is expired.
             We are developing checks for these states, so over time the correct status will be presented.
             For example, an expired RACF password now reports with a warning "expired" message.


To enable this feature:

.. code-block:: php

    $use_checkpassword = true;
