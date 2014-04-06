init:
	composer install
dev:
	php -S 127.0.0.1:3002 -t public public/index.php
deploy:
	# todo
clean:
	rm -rf cache/*
	touch cache/.gitkeep