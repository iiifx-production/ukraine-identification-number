<?php

use iiifx\Identification\Ukraine\Builder;

class BuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * Правильные данные
     *
     * @return array
     */
    public function getData ()
    {
        return [
            # $sex, $date, $age, $valid
            [ Builder::SEX_MALE, '2015-12-04', ( new DateTime( '2015-12-04' ) )->diff( new DateTime() )->y, true ],
            [ Builder::SEX_MALE, '1900-01-01', ( new DateTime( '1900-01-01' ) )->diff( new DateTime() )->y, true ],
            [ Builder::SEX_FEMALE, '2000-12-31', ( new DateTime( '2000-12-31' ) )->diff( new DateTime() )->y, true ],
            [ true, '2000-12-31', -1, false ],
            [ 'hello', '2000-12-31', false, false ],
            [ 1, '1801-02-03', 'hello', false ],
        ];
    }

    /**
     * @dataProvider getData
     */
    public function testFactory ( $sex, $date, $age, $valid )
    {
        if ( $valid ) {
            $this->assertInstanceOf( Builder::class, Builder::create( $sex, new DateTime( $date ) ) );
        }
    }

    /**
     * @dataProvider getData
     */
    public function testSetPersonSex ( $sex, $date, $age, $valid )
    {
        if ( $valid ) {
            $this->assertInstanceOf( Builder::class, Builder::create( $sex, new DateTime( $date ) ) );
        } else {
            $this->setExpectedException( InvalidArgumentException::class );
            Builder::create( $sex, new DateTime( $date ) );
        }
    }

    /**
     * @dataProvider getData
     */
    public function testSetPersonMaleFemale ( $sex, $date, $age, $valid )
    {
        if ( $valid ) {
            $builder = new Builder();
            if ( $sex === Builder::SEX_MALE ) {
                $builder->setPersonMale();
            } else {
                $builder->setPersonFemale();
            }
            $this->assertEquals( $sex, Builder::create( $sex )->getPersonSex() );
        } else {
            $this->setExpectedException( InvalidArgumentException::class );
            Builder::create( $sex, new DateTime( $date ) );
        }
    }

    /**
     * @dataProvider getData
     */
    public function testSetPersonAge ( $sex, $date, $age, $valid )
    {
        $builder = new Builder();
        if ( $valid ) {
            $builder->setPersonAge( $age );
            $this->assertEquals( $age, $builder->getPersonBirthDatetime()->diff( new DateTime() )->y );
        } else {
           $this->setExpectedException( InvalidArgumentException::class );
           $builder->setPersonAge( $age );
        }
    }

    /**
     * @dataProvider getData
     */
    public function testSetPersonBirthDatetime ( $sex, $date, $age, $valid )
    {
        if ( $valid ) {
            $builder = new Builder();
            $builder->setPersonBirthDatetime( new DateTime( $date ) );
            $this->assertEquals( $age, $builder->getPersonBirthDatetime()->diff( new DateTime() )->y );
        }
    }

    /**
     * @dataProvider getData
     */
    public function testCreateNumber ( $sex, $date, $age, $valid )
    {
        $builder = Builder::create();
        if ( $valid ) {
            $builder->setPersonSex( $sex );
            $builder->setPersonAge( $age );
            $this->assertEquals( 10, strlen( $builder->createNumber() ) );
        } else {
            $this->assertEquals( 10, strlen( $builder->createNumber() ) );
        }
    }
}
