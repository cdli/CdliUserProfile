CdliUserProfile
==================
Version 0.0.2 Created by the Centre for Distance Learning and Innovation (www.cdli.ca)

Introduction
------------

CdliUserProfile is an extension to [ZfcUser](http://github.com/ZF-Commons/ZfcUser) which provides an account profile editor

**NOTE: This module is still under heavy development, and is highly unlikely to work properly or safely at this time**

Installation Instructions
-------------------------

Installation of CdliUserProfile uses composer. For composer documentation, please refer to [getcomposer.org](http://getcomposer.org).

#### Installation Steps
1. cd my/project/directory
2. Add this project to your composer.json

    ```json
    "require": {
        "cdli/user-profile": "dev-master"
    }
   ```
3. Now tell composer to download CdliUserProfile by running the command:

    ```bash
    $ php composer.phar update
    ```
4. open `configs/application.config.php` and add the following key to your `modules`:

     ```php
     'CdliUserProfile',
     ```

#### Configuration
Checkout the default configuration file located at `vendor/cdli/user-profile/config/cdliuserprofile.global.php.dist`. You may want to copy this over to `config/autoload/cdliuserprofile.global.php`
