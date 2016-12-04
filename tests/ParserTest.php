<?php

use iiifx\Identification\Ukraine\Parser;

class ParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * Правильные данные
     *
     * @return array
     */
    public function getData ()
    {
        return [
            # $number, $valid, $sex, $date, $summ, $digit
            [ '0123456789', false, null, null, null, null ],
            [ 1234567890, false, null, null, null, null ],

            [ '3209019722', true, 2, '1987-11-10', 233, 2 ],
            [ '2534202383', true, 2, '1969-05-20', 179, 3 ],
            [ '3928187406', true, 2, '2007-07-19', 270, 6 ],
            [ '4052326370', true, 1, '2010-12-12', 197, 0 ],
            [ '0663355735', true, 1, '1918-02-28', 247, 5 ],
            [ '2917826131', true, 1, '1979-11-20', 243, 1 ],
        ];
    }

    /**
     * @dataProvider getData
     */
    public function testFactory ( $number, $valid, $sex, $date, $summ, $digit )
    {
        $this->assertInstanceOf( Parser::class, Parser::create( $number ) );
    }

    /**
     * @dataProvider getData
     */
    public function testGetNumber ( $number, $valid, $sex, $date, $summ, $digit )
    {
        $this->assertEquals( $number, Parser::create( $number )->getNumber() );
    }

    /**
     * @dataProvider getData
     */
    public function testGetControlSumm ( $number, $valid, $sex, $date, $summ, $digit )
    {
        if ( $valid ) {
            $this->assertEquals( $summ, Parser::create( $number )->getControlSumm() );
        }
    }

    /**
     * @dataProvider getData
     */
    public function testGetControlDigit ( $number, $valid, $sex, $date, $summ, $digit )
    {
        if ( $valid ) {
            $this->assertEquals( $digit, Parser::create( $number )->getControlDigit() );
        }
    }

    /**
     * @dataProvider getData
     */
    public function testIsValidNumber ( $number, $valid, $sex, $date, $summ, $digit )
    {
        $this->assertEquals( Parser::create( $number )->isValidNumber(), $valid );
    }

    /**
     * @dataProvider getData
     */
    public function testGetPersonSex ( $number, $valid, $sex, $date, $summ, $digit )
    {
        if ( $valid ) {
            $this->assertEquals( Parser::create( $number )->getPersonSex(), $sex );
            if ( $sex === Parser::SEX_MALE ) {
                $this->assertTrue( Parser::create( $number )->isPersonMale() );
                $this->assertFalse( Parser::create( $number )->isPersonFemale() );
            } else {
                $this->assertFalse( Parser::create( $number )->isPersonMale() );
                $this->assertTrue( Parser::create( $number )->isPersonFemale() );
            }
        } else {
            $this->assertEquals( Parser::create( $number )->getPersonSex(), null );
        }
    }

    /**
     * @dataProvider getData
     */
    public function testGetPersonAge ( $number, $valid, $sex, $date, $summ, $digit )
    {

        $datetime = new DateTime( $date . ' 00:00:00', new DateTimeZone( 'UTC' ) );
        if ( $valid ) {
            $this->assertEquals(
                Parser::create( $number )->getPersonAge(),
                (int) ( new DateTime( 'now', new DateTimeZone( 'UTC' ) ) )->modify( 'midnight' )->diff( $datetime )->y
            );
            $this->assertEquals(
                Parser::create( $number )->getPersonAge( ( new DateTime( 'now', new DateTimeZone( 'UTC' ) ) )->modify( 'midnight -1 year' ) ),
                (int) ( new DateTime( 'now', new DateTimeZone( 'UTC' ) ) )->modify( 'midnight -1 year' )->diff( $datetime )->y
            );
        } else {
            $this->assertEquals(
                Parser::create( $number )->getPersonAge(),
                null
            );
            $this->assertEquals(
                Parser::create( $number )->getPersonAge( ( new DateTime( 'now', new DateTimeZone( 'UTC' ) ) )->modify( 'midnight -1 year' ) ),
                null
            );
        }
    }

    /**
     * @dataProvider getData
     */
    public function testGetPersonBirthDatetime ( $number, $valid, $sex, $date, $summ, $digit )
    {
        if ( $valid ) {
            $this->assertEquals( Parser::create( $number )->getPersonBirthDatetime()->format( 'Y-m-d' ), $date );
        } else {
            $this->assertEquals( Parser::create( $number )->getPersonBirthDatetime(), null );
        }
    }

    /**
     * @dataProvider getData
     */
    public function testGetPersonBirth ( $number, $valid, $sex, $date, $summ, $digit )
    {
        if ( $valid ) {
            $this->assertEquals( Parser::create( $number )->getPersonBirth(), $date );
            $this->assertEquals( Parser::create( $number )->getPersonBirth( 'Y-m-d' ), $date );
        } else {
            $this->assertEquals( Parser::create( $number )->getPersonBirth(), null );
        }
    }
}
