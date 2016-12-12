all:
	@echo "complextest - download selenium.jar, start full infrastructure and tests"
	@echo "startserver - starts php built in server on 127.0.0.1:8000"
	@echo "stopserver - stops php built in server"
	@echo "downloadselenium - downloads selenium 2.53.1 into root directory as selenium.jar"
	@echo "startselenium - starts selenium server on port 4444 (selenium.jar in root required)"
	@echo "stopselenium - stops selenium server"

startserver:
	cd ./tests/selenidehtml && nohup php -S 127.0.0.1:8000 > /dev/null 2>&1 &

stopserver:
	pkill -f "php -S 127.0.0.1:8000"

downloadselenium:
	wget -O selenium.jar http://selenium-release.storage.googleapis.com/2.53/selenium-server-standalone-2.53.1.jar

startselenium:
	nohup java -jar ./selenium.jar -port 4444 > /dev/null 2>&1 &

stopselenium:
	pkill -f "java -jar ./selenium.jar -port 4444"

test:
	./vendor/phpunit/phpunit/phpunit -c ./phpunit.xml
	./vendor/bin/test-reporter

fulltest:
	@echo Downloading selenium...
	@make downloadselenium
	@echo Starting php built-in server...
	@make startserver
	@echo Starting selenium-standalone server...
	@make startselenium
	@echo Wait for selenium 10 secs...
	@sleep 10
	@echo Starting tests...
	@make test
	@echo Stopping selenium server...
	@make stopselenium
	@echo Stopping php server...
	@make stopserver
