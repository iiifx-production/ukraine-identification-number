<?php

namespace iiifx\Identification\Ukraine;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

class Parser
{
    /**
     * Номер ИНН
     *
     * @var string
     */
    protected $number;

    /**
     * Части номера, по цифрам
     *
     * @var string[]
     */
    protected $parts;

    /**
     * Корректный ли номер ИНН
     *
     * @var bool
     */
    protected $valid;

    /**
     * Контрольное число
     *
     * @var int|false
     */
    protected $control;

    /**
     * Пол владельца
     *
     * @var string|false
     */
    protected $sex;

    /**
     * Дата рождения владельца
     *
     * @var DateTimeImmutable|false
     */
    protected $datetime;

    /**
     * Фабрика
     *
     * @param string|int $number
     *
     * @return Parser
     */
    public static function create ( $number )
    {
        return new Parser( $number );
    }

    /**
     * Базовый конструктор
     *
     * @param string $number
     */
    public function __construct ( $number )
    {
        $this->number = (string) $number;
    }

    /**
     * @return int|string
     */
    public function getNumber ()
    {
        return $this->number;
    }

    /**
     * Определить корректный ли номер ИНН
     *
     * @return bool
     */
    public function isValid ()
    {
        if ( $this->valid === null ) {
            if ( $this->parseNumber() ) {
                $this->valid = $this->getControlDigit() === (int) $this->parts[ 9 ];
            }
        }
        return $this->valid;
    }

    /**
     * Получить пол владельца номера ИНН
     *
     * @return string
     */
    public function getSex ()
    {
        if ( $this->sex === null ) {
            $this->sex = false;
            if ( $this->isValid() ) {
                $this->sex = ( $this->parts[ 8 ] % 2 ) ? 'M' : 'F';
            }
        }
        return $this->sex;
    }

    /**
     * Мужчина ли владелец номера ИНН
     *
     * @return bool
     */
    public function isMale ()
    {
        return $this->getSex() === 'M';
    }

    /**
     * Женщина ли владелец номера ИНН
     *
     * @return bool
     */
    public function isFemale ()
    {
        return $this->getSex() === 'F';
    }

    /**
     * Получить возраст владельца номера ИНН
     *
     * @param DateTimeInterface|null $now
     *
     * @return int
     */
    public function getAge ( DateTimeInterface $now = null )
    {
        if ( $now === null ) {
            $now = new DateTime( 'now', new DateTimeZone( 'UTC' ) );
            $now->modify( 'midnight' );
        }
        return (int) $now->diff( $this->getBirthDatetime() )->y;
    }

    /**
     * Получить дату рождения владельца ИНН
     *
     * @return DateTimeImmutable|false
     */
    public function getBirthDatetime ()
    {
        if ( $this->datetime === null ) {
            $this->datetime = false;
            if ( $this->isValid() ) {
                $days = substr( $this->number, 0, 5 ) - 1;
                $datetime = new DateTimeImmutable(
                    '1900-01-01 00:00:00',
                    new DateTimeZone( 'UTC' )
                );
                $this->datetime = $datetime->modify( "+ {$days} days" );
            }
        }
        return $this->datetime;
    }

    /**
     * Распарсить номер ИНН
     *
     * @return bool
     */
    protected function parseNumber ()
    {
        if ( $this->parts === null ) {
            $this->parts = [];
            if ( preg_match( '/^\d{10}$/', $this->number ) === 1 ) {
                $this->parts = str_split( $this->number );
            }
        }
        return (bool) $this->parts;
    }

    /**
     * Получить контрольное число номера ИНН
     *
     * @return int
     */
    protected function getControlDigit ()
    {
        if ( $this->control === null ) {
            $this->control = false;
            if ( $this->parseNumber() ) {
                $summ =
                    $this->parts[ 0 ] * -1
                    + $this->parts[ 1 ] * 5
                    + $this->parts[ 2 ] * 7
                    + $this->parts[ 3 ] * 9
                    + $this->parts[ 4 ] * 4
                    + $this->parts[ 5 ] * 6
                    + $this->parts[ 6 ] * 10
                    + $this->parts[ 7 ] * 5
                    + $this->parts[ 8 ] * 7;
                $this->control = $summ % 11;
            }
        }
        return $this->control;
    }
}
