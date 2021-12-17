start:
	php -S localhost:8081 -t src/Blog/Infrastructure/Delivery/Web/Slim/Public

lint:
	composer phpcs

lint-fix:
	composer phpcbf
