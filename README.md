# Annotweet

A lightweight app that supports anonymous posting to an account on Twitter, and also provides a live feed of tweets from that account.

## Development

Requires [php 5.4+](http://php.net/) and [composer](https://getcomposer.org/). Run `make` to install dependencies.

## How It's Put Together

Annotweet is built using the [Slim microframework](http://slimframework.com/) and uses [Twig templates](http://twig.sensiolabs.org/). Twitter integration is provided by [twitter-php](https://github.com/dg/twitter-php).