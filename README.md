## Requirements

PHP 8 and later.

## How to set up the project

You can install the libraries via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer install
```

## Execute through endpoint
Normal trial (First phase of the exercise).

For this we need the symfony server working:

``` 
symfony serve -d
 ```
Now we can use an url like this:
```
https://127.0.0.1:8000/trial?plaintiff=KN&defendant=NVV
```

Conjecture to guess what's needed to win using # as a signature (Second stage of the exercise).
We will use '%23' to encode the character '#' in our url.

```
https://127.0.0.1:8000/trial?plaintiff=KN&defendant=N%23
```
## Execute using command line 

We will invoke our command (app:Trial) passing both contracts:
```bash
php bin/console app:Trial NNN NNK
```