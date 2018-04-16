.. image:: https://travis-ci.org/EMBL-EBI-TSI/aap-client-php.svg?branch=master
    :target: https://travis-ci.org/EMBL-EBI-TSI/aap-client-php

Overview
########

Tools used to test that the owncloud plugin actually checks correctly what should be checked.

Cryptographic files here shouldn't be used in production, they're just for testing :)

Installing dependencies
#######################

Go to the project root and then

``$ composer install``

Usage
#####

To show how claims afects the validity of a JWT token run:

``$ php examples/ShowTokens.php``

If, for some reason you also want to see the contents of the tokens, you can switch the ``$print`` variable to ``true``.

To run the verification tests use phpunit like so:

``$ phpunit tests/TokenTest``

More validity checks can be done following the structure in ``src/Checker/PresentSubjectChecker.php`` and adding them in the function ``getClaimChecks()`` in ``src/Token/TokenTester.php``.

More test tokens/claims can be added in ``src/Claim/ClaimFactory.php`` along with the expected result.

Including the library
^^^^^^^^^^^^^^^^^^^^^
In order to use this library add this git repository to ``composer.json``, like so:

.. code:: json

 {
   "repositories": [
     {
       "type": "vcs",
       "url" : "git@github.com:EMBL-EBI-TSI/aap-client-php.git"
     }
   ],
   "require": {
     "ebi/jwt": "^v2.0.0"
   }
 }


Compatibility
^^^^^^^^^^^^^
Versions v1.x.x are compatible with PHP 5.6, 7.0 and 7.1, v2.x.x with 7.1 and 7.2.
This is due to breaking changes in 7.2 and the dependencies used.
