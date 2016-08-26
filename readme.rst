Overview
########

Tools used to test that the owncloud plugin actually checks correctly what should be checked.

Cryptographic files here shouldn't be used in production, they're just for testing :)

Installing dependencies
#######################

Go to the project root and then

``$ composer install``

Testing
#######

To run the tests go to ``src/`` and then

``$ php test.php``

To try more tokens modify ``src/Token/TokenFactory.php``.

More validity checks can be done following the structure in ``src/Checker/PresentSubjectChecker.php`` and adding them in the function ``getClaimChecks()`` in ``src/Token/TokenTester.php``.

More test tokens/claims can be added in ``src/Claim/ClaimFactory.php`` to see how the validator will behave.
