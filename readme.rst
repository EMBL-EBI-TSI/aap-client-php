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
Because this is a private repository, composer will fail when trying to retrieve the library as a dependency.

If you're a developer you can add your public key to your github account.
As long as you have access to the repo and ssh is set up to correctly use identities you're good to go.

If, however, you want to deploy it to a server (with Jenkins, for example), you'll have to ask the owner of this repo to add a deploy key to it.
Then you'll have to add the public key to your ``composer.json``, similar to this:

.. code:: json

 {
   "repositories": [
     {
       "type": "vcs",
       "url" : "git@github.com:EMBL-EBI-TSI/workbench-jwt-test.git",
       "no-api": true,
       "options": {
         "username": "user",
         "pubkey_file" : "/home/user/.ssh/ids/ebijwt/id_rsa.pub",
       }
     }
   ],
   "require": {
     "ebi/jwt": "^v0.2.0"
   }
 }

