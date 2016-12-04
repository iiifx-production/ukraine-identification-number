<?php

namespace iiifx\Identification\Ukraine;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;

class Builder
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
     * @var int
     */
    protected $personSex;

    /**
     * @var DateTimeImmutable
     */
    protected $birthDatetime;

    /**
     * @param int|null               $sex
     * @param DateTimeInterface|null $datetime
     *
     * @return static
     */
    public static function create ( $sex = null, DateTimeInterface $datetime = null )
    {
        $builder = new static();
        if ( $sex ) {
            $builder->setPersonSex( $sex );
        }
        if ( $datetime ) {
            $builder->setPersonBirthDatetime( $datetime );
        }
        return $builder;
    }

    /**
     * @param $sex
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function setPersonSex ( $sex )
    {
        $allow = [
            static::SEX_MALE,
            static::SEX_FEMALE,
        ];
        if ( in_array( $sex, $allow, true ) ) {
            $this->personSex = $sex;
            return $this;
        }
        throw new InvalidArgumentException( '$sex must be Builder::SEX_MALE or Builder::SEX_FEMALE' );
    }

    /**
     * @return $this
     */
    public function setPersonMale ()
    {
        $this->personSex = static::SEX_MALE;
        return $this;
    }

    /**
     * @return $this
     */
    public function setPersonFemale ()
    {
        $this->personSex = static::SEX_FEMALE;
        return $this;
    }

    /**
     * @param int $age
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function setPersonAge ( $age )
    {
        if ( is_int( $age ) && $age >= 0 ) {
            $datetime = ( new DateTimeImmutable( 'now' ) )->modify( "-{$age} years -1 day" );
            return $this->setPersonBirthDatetime( $datetime );
        }
        throw new InvalidArgumentException( '$age must be positive integer' );
    }

    /**
     * @param DateTimeInterface $datetime
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function setPersonBirthDatetime ( DateTimeInterface $datetime )
    {
        if ( $datetime->format( 'Y' ) >= 1900 ) {
            if ( !$datetime instanceof DateTimeImmutable ) {
                $datetime = new DateTimeImmutable( $datetime->format( 'Y-m-d H:i:s' ) );
            }
            $this->birthDatetime = $datetime;
            return $this;
        }
        throw new InvalidArgumentException( 'Birthday must be equal or greater than 1900-01-01' );
    }

    /**
     * Format: {DDDDD}{RRR}{S}{C}
     *
     * {DDDDD} - Days from 1900-01-01
     * {RRR} - Any nambers
     * {S} - Person sex
     * {C} - Control digit
     *
     * @return string
     */
    public function createNumber ()
    {
        if ( $this->birthDatetime ) {
            $days = (int) $this->birthDatetime
                ->diff( new DateTime( '1900-01-01 00:00:00' ) )
                ->days;
        } else {
            $max = (int) ( new DateTime( '1900-01-01 00:00:00' ) )
                ->diff( new DateTime() )
                ->days;
            $days = mt_rand( 0, $max );
        }
        $DDDDD = sprintf( '%1$05d', $days + 2 );
        $RRR = sprintf( '%1$03d', mt_rand( 0, 999 ) );
        if ( !( $sex = $this->personSex ) ) {
            $list = [ static::SEX_MALE => 0, static::SEX_FEMALE => 0 ];
            $sex = array_rand( $list );
        }
        if ( $sex === static::SEX_FEMALE ) {
            $list = [ 0 => 0, 2 => 0, 4 => 0, 6 => 0, 8 => 0 ];
        } else {
            $list = [ 1 => 0, 3 => 0, 5 => 0, 7 => 0, 9 => 0 ];
        }
        $S = array_rand( $list );
        $number = "{$DDDDD}{$RRR}{$S}";
        $summ =
            ( $number{0} * -1 ) +
            ( $number{1} * 5 ) +
            ( $number{2} * 7 ) +
            ( $number{3} * 9 ) +
            ( $number{4} * 4 ) +
            ( $number{5} * 6 ) +
            ( $number{6} * 10 ) +
            ( $number{7} * 5 ) +
            ( $number{8} * 7 );
        $C = ( $summ % 11 ) % 10;
        return "{$number}{$C}";
    }

    /**
     * @return int|null
     */
    public function getPersonSex ()
    {
        return $this->personSex;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getPersonBirthDatetime ()
    {
        return $this->birthDatetime;
    }
}
