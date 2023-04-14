SHELL := /bin/bash

start:
	@echo "make [option]"
	@echo "OPTIONS:"
	@echo '	install         - installing new instance of api'
	@echo '	installTest     - installing only test instance of api'
	@echo '	installNoTest   - installing test and dev instance of api without running tests'
	@echo '	tests           - make all tests'
	@echo '	migration       - create doctrine migration'
	@echo '	migrate         - migrate database'
	@echo '	serverStart     - migrate database'
	@echo '	serverStop      - migrate database'
	@echo '	entity          - create entity'
unitTests:
	symfony run bin/phpunit
tests: unitTests
	@echo 'Test Completed'
migration:
	symfony console make:migration
migrate:
	symfony console doctrine:migrations:migrate
	APP_ENV=test symfony console doctrine:migrations:migrate
serverStart:
	symfony server:start -d
serverStop:
	symfony server:stop
entity:
	symfony console make:entity
install: tests
	./scripts/INSTALL.sh
installNoTest:
	./scripts/INSTALL.sh