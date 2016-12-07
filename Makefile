all:
	@echo "startserver - starts php built in server on 127.0.0.1:8000"
	@echo "stopserver - stops php built in server"
	@echo "startselenium - starts selenium server on port 4444 (selenium.jar in root required)"
	@echo "stopselenium - stops selenium server"

startserver:
	cd ./tests/selenidehtml && nohup php -S 127.0.0.1:8000 > /dev/null 2>&1 &

stopserver:
	pkill -f "php -S 127.0.0.1:8000"

startselenium:
	nohup java -jar ./selenium.jar -port 4444 > /dev/null 2>&1 &

stopselenium:
	pkill -f "java -jar ./selenium.jar -port 4444"

phpunit:
	./vendor/phpunit/phpunit/phpunit ./tests/SelenideTest.php