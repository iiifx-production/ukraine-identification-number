<?php

use iiifx\Identification\Ukraine\Parser;

class ParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * Правильные данные
     *
     * @return array
     */
    public function getDataProviders ()
    {
        return [
            [ '3080310794', 'M', '1984-05-02', true ],
        ];
    }

    /**
     * @dataProvider getDataProviders
     */
    public function testFactory ( $number, $sex, $date, $valid )
    {
        $this->assertInstanceOf( Parser::class, Parser::create( $number ) );
    }

    /**
     * @dataProvider getDataProviders
     */
    public function testGetNumber ( $number, $sex, $date, $valid )
    {
        $this->assertEquals( $number, Parser::create( $number )->getNumber() );
    }

    /**
     * @dataProvider getDataProviders
     */
    public function testIsValid ( $number, $sex, $date, $valid )
    {
        $this->assertEquals( Parser::create( $number )->isValid(), $valid );
    }

    /**
     * @dataProvider getDataProviders
     */
    public function testGetSex ( $number, $sex, $date, $valid )
    {
        $this->assertEquals( Parser::create( $number )->getSex(), $sex );
        if ( $sex === 'M' ) {
            $this->assertTrue( Parser::create( $number )->isMale() );
            $this->assertFalse( Parser::create( $number )->isFemale() );
        } else {
            $this->assertFalse( Parser::create( $number )->isMale() );
            $this->assertTrue( Parser::create( $number )->isFemale() );
        }
    }

    /**
     * @dataProvider getDataProviders
     */
    public function testGetAge ( $number, $sex, $date, $valid )
    {
        $datetime = new DateTime( $date . ' 00:00:00', new DateTimeZone( 'UTC' ) );
        $this->assertEquals(
            Parser::create( $number )->getAge(),
            (int) ( new DateTime( 'now', new DateTimeZone( 'UTC' ) ) )->modify( 'midnight' )->diff( $datetime )->y
        );
    }

    /**
     * @dataProvider getDataProviders
     */
    public function testGetBirthDatetime ( $number, $sex, $date, $valid )
    {
        $this->assertEquals( Parser::create( $number )->getBirthDatetime()->format( 'Y-m-d' ), $date );
    }
}
