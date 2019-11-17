# Github Connector
My first trial of plugin on wordpress: adds a widget that display latests commits from a  public github Account.


## Installing
Clone the project into ``wp-content/plugins/``

Using KnpLabs/php-github-api for  github requests, you need to have composer installed and run ``composer install``

## next points I want to test
* [ ] replace php-github-api and use directly Guzzle
* [ ] add more complex options when parametering widget
* [ ] add a refresh button with the widget
* [ ] try WP-Cli  in order to add some unit tests
