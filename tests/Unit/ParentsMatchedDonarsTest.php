<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Traits\ParentsToBeMatchedDonerTrait;

class ParentsMatchedDonarsTest extends TestCase
{
    use ParentsToBeMatchedDonerTrait;

    /**
     * Test location value.
     *
     * @return void
     */
    public function testLocationValue() {
        /* If donar location matched with parents preference */
        $this->assertEquals(LOCATION_VALUE, $this->getLocationValue(1, LOCATION_PREFERENCE));
        /* If donar location does not matche with parents preference */
        $this->assertEquals(LOCATION_VALUE * 1/3, $this->getLocationValue(3, LOCATION_PREFERENCE));
    }

    /**
     * Test age value.
     *
     * @return void
     */
    public function testAgeValue() {
        /* If donar age matched with parents preference */
        $this->assertEquals(AGE_VALUE, $this->getAgeValue(22,AGE_PREFERENCE));
        $this->assertEquals(AGE_VALUE, $this->getAgeValue(25,AGE_PREFERENCE));
        $this->assertEquals(AGE_VALUE, $this->getAgeValue(30,AGE_PREFERENCE));
        /* If donar age does not matche with parents preference */
        $this->assertEquals(AGE_VALUE * 1/3, $this->getAgeValue(70,AGE_PREFERENCE));
    }

    /**
     * Test race value.
     *
     * @return void
     */
    public function testRaceValue() {
        /* If donar race matched with parents preference */
        $this->assertEquals(RACE_VALUE, $this->getRaceValue(TWO,RACE_PREFERENCE));
        /* If donar race does not match with parents preference */
        $this->assertEquals(RACE_VALUE * 1/3, $this->getRaceValue(SIX,RACE_PREFERENCE));
    }

    /**
     * Test ethnicity value.
     *
     * @return void
     */
    public function testEthnicityValue() {
        /* If donar ethnicity matched with parents preference */
        $this->assertEquals(ETHNICITY_VALUE, $this->getEthnicityValue(TWO,THREE,ETHNICITY_PREFERENCE));
        /* If donar ethnicity does not match with parents preference */
        $this->assertEquals(ETHNICITY_VALUE * 1/3, $this->getEthnicityValue(ONE,FIVE,ETHNICITY_PREFERENCE));
    }

    /**
     * Test height value.
     *
     * @return void
     */
    public function testHeightValue() {
        /* If donar height matched with patient preference */
        $this->assertEquals(HEIGHT_VALUE, $this->getHeightValue(65,HEIGHT_PREFERENCE));
        /* If donar height does not match with preference */
        $this->assertEquals(HEIGHT_VALUE * 1/3, $this->getHeightValue(55,HEIGHT_PREFERENCE));
    }

    /**
     * Test hair colour value.
     *
     * @return void
     */
    public function testHairColourValue() {
        /* If donar hair colour matched with parents preference */
        $this->assertEquals(HAIR_COLOUR_VALUE, $this->getHairColourValue(ONE, HAIR_COLOUR_PREFERENCE));
        /* If donar hair colour does not match with patrents preference */
        $this->assertEquals(HAIR_COLOUR_VALUE * 1/3, $this->getHairColourValue(THREE,HAIR_COLOUR_PREFERENCE));
    }

    /**
     * Test eye colour value.
     *
     * @return void
     */
    public function testEyeColourValue() {
        /* If donar eye colour matched with parents preference */
        $this->assertEquals(EYE_COLOUR_VALUE, $this->getEyeColourValue(ONE, EYE_COLOUR_PREFERENCE));
        /* If donar eye colour does not match with patrents preference */
        $this->assertEquals(EYE_COLOUR_VALUE * 1/3, $this->getEyeColourValue(THREE, EYE_COLOUR_PREFERENCE));
    }

     /**
     * Test education value.
     *
     * @return void
     */
    public function testEducationValue() {
        /* If donar education matched with parents preference */
        $this->assertEquals(EDUCATION_VALUE, $this->getEducationValue(ONE, EDUCATION_PREFERENCE));
        /* If donar education does not match with patrents preference */
        $this->assertEquals(EDUCATION_VALUE * 1/3, $this->getEducationValue(THREE, EDUCATION_PREFERENCE));
    }
}
