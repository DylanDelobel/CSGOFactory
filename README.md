CSGOFactory Application
========================

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/0fdee7da60e84cfe9d89344ee38ff8d1)](https://www.codacy.com/app/dylan-delobel66/CSGOFactory?utm_source=github.com&utm_medium=referral&utm_content=DylanDelobel/CSGOFactory&utm_campaign=badger)

The "CSGOFactory Application" created to learn the Symfony framework

Symfony 3 Standart Edition based eCommerce project.

Requirements
------------

  * PHP 5.5.9 or higher

Installation
------------

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s http://getcomposer.org/installer | php

Then run the following commands:
	
    composer self-update
    composer update
	php bin/console doctrine:database:create
	php bin/console doctrine:schema:update --force

You can load some data if you want:
	
	php bin/console doctrine:fixture:load
	
Two account are initialized with the fixture

  * user/user
  * admin/admin
    
    
[1]:  http://getcomposer.org/
