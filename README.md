# phpSelenide

## Element / Collection
* find(By $locator) - поиск одного элемента
* findAll(By locator) - поиск множества элементов
* click() - клик по элементу
* doubleClick() - двойной клик по элементу
* exists() - проверяет существование элемента на странице
* isDisplayed() - проверяет, что элемент виден на странице
* attribute($attrName) - получить значение атрибута элемента
* val() - получить значение элемента (для input - @value, для select - @value выбранного option)


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
