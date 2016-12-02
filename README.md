# Identification Number: Ukraine

Парсер идентификационного номера налогоплательщика Украины

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/746be262-9725-4d22-9ce1-e7eb07dc4858/big.png)](https://insight.sensiolabs.com/projects/746be262-9725-4d22-9ce1-e7eb07dc4858)

[![Latest Version on Packagist][ico-version]][link-packagist] [![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads] 

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

[![Build Status][ico-travis]][link-travis] [![Code Coverage][ico-codecoverage]][link-scrutinizer]

## Лицензия

[![Software License][ico-license]](LICENSE.md)


[ico-version]: https://img.shields.io/packagist/v/iiifx-production/ukraine-identification-number.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-downloads]: https://img.shields.io/packagist/dt/iiifx-production/ukraine-identification-number.svg
[ico-travis]: https://travis-ci.org/iiifx-production/ukraine-identification-number.svg
[ico-scrutinizer]: https://scrutinizer-ci.com/g/iiifx-production/ukraine-identification-number/badges/quality-score.png?b=master
[ico-codecoverage]: https://scrutinizer-ci.com/g/iiifx-production/ukraine-identification-number/badges/coverage.png?b=master

[link-packagist]: https://packagist.org/packages/iiifx-production/ukraine-identification-number
[link-downloads]: https://packagist.org/packages/iiifx-production/ukraine-identification-number
[link-travis]: https://travis-ci.org/iiifx-production/ukraine-identification-number
[link-scrutinizer]: https://scrutinizer-ci.com/g/iiifx-production/ukraine-identification-number/?branch=master
