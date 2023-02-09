
.PHONY: all
all: vendor/autoload.php

vendor/autoload.php: bin/composer composer.lock
	./bin/composer install
	# TODO touch vendor/autoload.php?

bin/composer:
	-mkdir bin
	wget https://getcomposer.org/composer-stable.phar -O bin/composer
	chmod a+x bin/composer
