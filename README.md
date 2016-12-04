# Identification Number: Ukraine

Идентификационный номер налогоплательщика Украины. Парсер и генератор ИНН.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/746be262-9725-4d22-9ce1-e7eb07dc4858/big.png)](https://insight.sensiolabs.com/projects/746be262-9725-4d22-9ce1-e7eb07dc4858)

[![Latest Version on Packagist][ico-version]][link-packagist] [![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads] 

## Установка

Используя Composer:

``` bash
$ composer require "iiifx-production/ukraine-identification-number:0.*"
```

## Использование

Парсер ИНН:

``` php
use iiifx\Identification\Ukraine\Parser;

# Номер ИНН
$number = '2245134075';

# Создаем парсер
$parser = Parser::create( $number );
# Или так
$parser = new Parser( $number );

# Проверяем правильность ИНН
if ( $parser->isValidNumber() ) {

    $parser->getNumber(); # 2245134075

    # Определяем пол владельца ИНН
    $parser->getPersonSex(); # Parser::SEX_MALE
    $parser->isPersonMale(); # true
    $parser->isPersonFemale(); # false

    # Определяем возраст и дату рождения
    $parser->getPersonAge(); # 55
    $parser->getPersonBirth( 'Y-m-d' ); # 1961-06-20
    $parser->getPersonBirthDatetime()->format( 'd.m.Y H:i:s' ); # 20.06.1961 00:00:00

    # Контрольная сумма и число
    $parser->getControlSumm(); # 192
    $parser->getControlDigit(); # 5
}
```

Генератор ИНН:

``` php
use iiifx\Identification\Ukraine\Builder;

# Создаем генератор
$builder = new Builder();
# Или вот так
$builder = Builder::create( Builder::SEX_MALE, new DateTime( '2010-05-12' ) );

# Указывам пол
$builder->setPersonSex( Builder::SEX_MALE );
$builder->setPersonMale();
$builder->setPersonFemale();

# Указываем возраст
$builder->setPersonAge( 55 );
$builder->setPersonBirthDatetime( new DateTime( '1962-11-03' ) );

# Генерируем ИНН
$builder->createNumber(); # 2295209520
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
