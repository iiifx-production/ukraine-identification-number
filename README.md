# Identification Number: Ukraine

Парсер идентификационного номера налогоплательщика Украины

## Установка

Используя Composer:

``` bash
$ composer require "iiifx-production/ukraine-identification-number:0.*"
```

## Использование

``` php
use iiifx\Identification\Ukraine\Parser;

# Создаем парсер ИНН
$parser = Parser::create( '0123456789' );

# Получаем все данные
$parser->getNumber(); # '0123456789'
$parser->isValid(); # true
$parser->getSex(); # 'M'
$parser->isMale(); # true
$parser->getAge(); # 32
$parser->getBirthDatetime()->format( 'd.m.Y' ); # '02.05.1984'
```

## Тесты

@TODO

## Лицензия

MIT
