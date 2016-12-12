# phpSelenide

[![License](https://poser.pugx.org/razielsd/phpselenide/license)](https://packagist.org/packages/razielsd/phpselenide)
[![Build Status](https://travis-ci.org/razielsd/phpSelenide.svg?branch=master)](https://travis-ci.org/razielsd/phpSelenide)
[![Code Climate](https://codeclimate.com/github/razielsd/phpSelenide/badges/gpa.svg)](https://codeclimate.com/github/razielsd/phpSelenide)
[![Test Coverage](https://codeclimate.com/github/razielsd/phpSelenide/badges/coverage.svg)](https://codeclimate.com/github/razielsd/phpSelenide/coverage)
[![Latest Stable Version](https://poser.pugx.org/razielsd/phpselenide/v/stable)](https://packagist.org/packages/razielsd/phpselenide)
[![Total Downloads](https://poser.pugx.org/razielsd/phpselenide/downloads)](https://packagist.org/packages/razielsd/phpselenide)



## Install
Add to composer.json __razielsd/phpselenide__, example:
```
{
    "name": "my project",
    "description": "Selenide example",
    "require": {
        "razielsd/phpselenide": "~0.3"
    }

```
Update composer:
```
composer install
```

## How to test
* composer install
* make fulltest

Other options you can see in Makefile or run `make`


## Collection
* find(By $locator) - поиск одного элемента
* findAll(By locator) - поиск множества элементов
* click() - клик по элементу
* doubleClick() - двойной клик по элементу
* exists() - проверяет существование элемента на странице
* isDisplayed() - проверяет, что элемент виден на странице
* attribute($attrName) - получить значение атрибута элемента
* val() - получить значение элемента (для input - @value, для select - @value выбранного option)
* get($index) - получить элемент коллекции
* getCollection() - получить все найденные элементы
* getCollectionNotEmpty - получить все найденные элементы, с проверкой что хотя бы один элемент найден

## Condition list
* size($size)
* sizeGreaterThen($size)
* sizeGreaterThenOrEqual($size)
* sizeLessThen($size)
* sizeLessThenOrEqual($size)
* text($text)
* withText($text)
* value($value)
* attribute($attrName, $value)
* visible()
* checked()
* child(By $locator)

## ToDo
* Execute Javascript
* Get element html source
* Wait()
* Element locator for assertion error
* iframe/frame support 
