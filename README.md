Symfony3 - Rock Paper Scissors Spock Lizard
=====

A Symfony3 implementation of "Rock Paper Scissors Spock Lizard".

### Setup Instructions

#### Create database and tables

    $ php bin/console doctrine:database:create
    $ php bin/console doctrine:schema:create
    
#### Install composer dependencies

    $ composer install

#### Install gulp and other node dependencies

    $ cd rpssl;
    $ npm install
    
##### Run gulp, watch occurs automatically

    $ gulp
    
* Note: Pre-compiled assets are located in ./app/Resources/assets/ and are compiled to ./web/assets/
