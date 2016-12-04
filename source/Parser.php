<?php

namespace iiifx\Identification\Ukraine;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

class Parser
{
    /**
     * Male
     */
    const SEX_MALE = 1;

    /**
     * Female
     */
    const SEX_FEMALE = 2;

    /**
     * @var string
     */
    protected $number;

    /**
     * @var bool
     */
    protected $isValid;

    /**
     * @var int
     */
    protected $controlSumm;

    /**
     * @var int
     */
    protected $controlDigit;

    /**
     * @var int
     */
    protected $personSex;

    /**
     * @var DateTimeImmutable
     */
    protected $birthDatetime;

    /**
     * @param string $number
     *
     * @return Parser
     */
    public static function create ( $number )
    {
        return new Parser( $number );
    }

    /**
     * @param string $number
     */
    public function __construct ( $number )
    {
        $this->number = (string) $number;
        $this->parseNumber();
    }

    /**
     * Parser
     */
    protected function parseNumber ()
    {
        if ( preg_match( '/^\d{10}$/', $this->number ) === 1 ) {
            $this->controlSumm =
                ( $this->number{0} * -1 ) +
                ( $this->number{1} * 5 ) +
                ( $this->number{2} * 7 ) +
                ( $this->number{3} * 9 ) +
                ( $this->number{4} * 4 ) +
                ( $this->number{5} * 6 ) +
                ( $this->number{6} * 10 ) +
                ( $this->number{7} * 5 ) +
                ( $this->number{8} * 7 );
            $this->controlDigit = ( $this->controlSumm % 11 ) % 10;
            $this->isValid = $this->controlDigit === (int) $this->number{9};
            if ( $this->isValid ) {
                $this->personSex = ( $this->number{8} % 2 ) ? static::SEX_MALE : static::SEX_FEMALE;
                $datetime = new DateTimeImmutable(
                    '1900-01-01 00:00:00',
                    new DateTimeZone( 'UTC' )
                );
                $days = substr( $this->number, 0, 5 ) - 1;
                $this->birthDatetime = $datetime->modify( "+ {$days} days" );
            }
        }
    }

    /**
     * @return string|null
     */
    public function getNumber ()
    {
        return $this->number;
    }

    /**
     * @return int|null
     */
    public function getControlDigit ()
    {
        return $this->controlDigit;
    }

    /**
     * @return int|null
     */
    public function getControlSumm ()
    {
        return $this->controlSumm;
    }

    /**
     * @return bool|null
     */
    public function isValidNumber ()
    {
        return $this->isValid;
    }

    /**
     * @return int|null
     */
    public function getPersonSex ()
    {
        return $this->personSex;
    }

    /**
     * @return bool|null
     */
    public function isPersonMale ()
    {
        return $this->getPersonSex() === static::SEX_MALE;
    }

    /**
     * @return bool|null
     */
    public function isPersonFemale ()
    {
        return $this->getPersonSex() === static::SEX_FEMALE;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getPersonBirthDatetime ()
    {
        return $this->birthDatetime;
    }

    /**
     * @param string $format
     *
     * @return string|null
     */
    public function getPersonBirth ( $format = 'Y-m-d' )
    {
        if ( $datetime = $this->getPersonBirthDatetime() ) {
            return $datetime->format( $format );
        }
        return null;
    }

    /**
     * @param DateTimeInterface|null $now
     *
     * @return int|null
     */
    public function getPersonAge ( DateTimeInterface $now = null )
    {
        if ( $datetime = $this->getPersonBirthDatetime() ) {
            if ( $now === null ) {
                $now = new DateTime( 'now', new DateTimeZone( 'UTC' ) );
            }
            return (int) $now->diff( $datetime )->y;
        }
        return null;
    }
}
