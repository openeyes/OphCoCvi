# DEPRECATED

_this module has now become a part of the core of [OpenEyes](https://github.com/openeyes/OpenEyes), and no further development will take place on this repository_ At the time of deprecation, it was a part of 1.16, so the code will be found in the relevant release branch, and later on in the main branches and all future releases.

# OphCoCvi

An OpenEyes event module to generate electronic CVI (Certificate of Visual Impairment)

## Configuration

Add the module to the application configuration:

    'OphCoCvi' => array('class' => '\OEModule\OphCoCvi\OphCoCviModule')

Install the following packages:

    sudo apt-get install libreoffice-core libreoffice-common libreoffice-writer php5-mcrypt

Run composer update (need to get the QR codes)

Add the following section with proper data into your config/local/common.php params section:

    'portal' => array(
                'uri' => 'http://api.localhost:8000',
                'endpoints' => array(
                    'auth' => '/oauth/access',
                    'examinations' => '/examinations/searches',
                    'signatures' => '/signatures/searches'
                ),
                'credentials' => array(
                    'username' => 'user@example.com',
                    'password' => 'apipass',
                    'grant_type' => 'password',
                    'client_id' => 'f3d259ddd3ed8ff3843839b',
                    'client_secret' => '4c7f6f8fa93d59c45502c0ae8c4a95b',
                ),
            ),

## User Permissions

Bizrules have been implemented to support the management of user permissions on this event. Specifically this allows for automatic permissions to be granted the right to edit the clinical detail of cvis. See ```config/common.php``` for details.

The sections of the form are divided so that only users with clerical permissions can edit the clerical element of the event. It's possible that if a user has clinical permission they should have clerical as well. This will require a small change if it proves necessary.

## Code

### Controller complexity

Due to the user permissions regarding the different elements in the event, several of the standard methods have been overridden in the default controller to manipulate which elements are created/edited depending on the user permissions.

### Manager Class

In an effort to abstract the business logic, much of it has been encapsulated in a manager class. This is a new structure that has not been entirely adhered to during initial development, however the intent is for this to aid test-ability, subject to time and resources, as well make the code overall cleaner.

### Refactoring

There are some significant refactoring needs for how various of the related data is assigned and rendered for clinical and clerical elements (disorders and patient factors respectively). 

At the moment, several static model calls will be impacting query volume, and it has led to inconsistency between how the data is retrieved depending on the context. This should be addressed.


## Status

This module is in initial development and not intended for any use outside of core development. Feel free to take a look as code is developed though.

