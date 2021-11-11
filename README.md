# Identification Number: Ukraine

Идентификационный номер налогоплательщика Украины. Парсер и генератор ИНН.

[comment]: <> ([![SensioLabsInsight]&#40;https://insight.sensiolabs.com/projects/746be262-9725-4d22-9ce1-e7eb07dc4858/big.png&#41;]&#40;https://insight.sensiolabs.com/projects/746be262-9725-4d22-9ce1-e7eb07dc4858&#41;)

[comment]: <> ([![Latest Version on Packagist][ico-version]][link-packagist] [![Build Status][ico-travis]][link-travis])

[comment]: <> ([![Total Downloads][ico-downloads]][link-downloads] )

## Алгоритм
Номер ИНН состоит из десяти знаков
```
$number = "{0}{1}{2}{3}{4}{5}{6}{7}{8}{9}"
```

Вычисляем контрольную сумму: суммируем множители первых 9 знаков ИНН
```
$sum =
    ( $number{0} * -1 ) +
    ( $number{1} * 5 ) +
    ( $number{2} * 7 ) +
    ( $number{3} * 9 ) +
    ( $number{4} * 4 ) +
    ( $number{5} * 6 ) +
    ( $number{6} * 10 ) +
    ( $number{7} * 5 ) +
    ( $number{8} * 7 )
```
Получаем контрольное число: делим контрольную сумму на 11 по модулю, потом на 10
```
$digit = ( $sum % 11 ) % 10
```
Если контрольное число и 10 знак в ИНН совпадает - номер правильный
```
$digit === $number{9}
```
Пол определяется по 9 знаку в ИНН: четное - женщина, нечетное - мужчина
```
$sex = ( $number{8} % 2 ) ? MALE : FEMALE
```
Возраст определяется по первым 5 знакам ИНН: это количество дней от 1899-12-31


## Установка

Используя Composer:

``` bash
$ composer require iiifx-production/ukraine-identification-number
```

## Использование

Парсер ИНН:

``` php
use iiifx\Identification\Ukraine\Parser;

# Номер ИНН
$number = '2245134075';

# Создаем парсер
$parser = Parser::create($number);
# Или так
$parser = new Parser($number);

# Проверяем правильность ИНН
if ($parser->isValidNumber()) {
    echo $parser->getNumber(); # 2245134075

    # Определяем пол владельца ИНН
    echo $parser->getPersonSex(); # Parser::SEX_MALE
    echo $parser->isPersonMale(); # true
    echo $parser->isPersonFemale(); # false

    # Определяем возраст и дату рождения
    echo $parser->getPersonAge(); # 55
    echo $parser->getPersonBirth('Y-m-d'); # 1961-06-20
    echo $parser->getPersonBirthDatetime()->format('d.m.Y H:i:s'); # 20.06.1961 00:00:00

    # Контрольная сумма и число
    echo $parser->getControlSum(); # 192
    echo $parser->getControlDigit(); # 5
}
```

Генератор ИНН:

``` php
use iiifx\Identification\Ukraine\Builder;

# Создаем генератор
$builder = new Builder();
# Или вот так
$builder = Builder::create(Builder::SEX_MALE, new DateTime('2010-05-12'));

# Указывам пол
$builder->setPersonSex(Builder::SEX_MALE);
$builder->setPersonMale();
$builder->setPersonFemale();

# Указываем возраст
$builder->setPersonAge(55);
$builder->setPersonBirthDatetime(new DateTime('1962-11-03'));

# Генерируем ИНН
echo $builder->createNumber(); # 2295209520
```

## Тесты
Удалены, нужно перенастраивать

[comment]: <> ([![Build Status][ico-travis]][link-travis] [![Code Coverage][ico-codecoverage]][link-scrutinizer])

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

