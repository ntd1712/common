<?php

namespace Chaos\Test;

/**
 * Class FunctionTest
 * @author ntd1712
 */
class FunctionTest extends \PHPUnit_Framework_TestCase
{
    // <editor-fold desc="defines.php" defaultstate="collapsed">

    public function testClassesExist()
    {
        $this->assertTrue(interface_exists(CHAOS_BASE_OBJECT_COLLECTION_INTERFACE));
        $this->assertTrue(interface_exists(CHAOS_BASE_OBJECT_ITEM_INTERFACE));
        $this->assertTrue(function_exists(CHAOS_JSON_DECODER)
            || class_exists(CHAOS_JSON_DECODER));
        $this->assertTrue(function_exists(CHAOS_JSON_ENCODER)
            || class_exists(CHAOS_JSON_ENCODER));
        $this->assertTrue(class_exists(CHAOS_READ_EVENT_ARGS));
        $this->assertTrue(class_exists(DOCTRINE_ARRAY_COLLECTION));
        $this->assertTrue(class_exists(DOCTRINE_PERSISTENT_COLLECTION));
        $this->assertTrue(class_exists(DOCTRINE_DRIVER_MANAGER));
        $this->assertTrue(class_exists(DOCTRINE_ENTITY_MANAGER));
        $this->assertTrue(interface_exists(DOCTRINE_PROXY));
    }

    /**
     * @dataProvider testMatchDateDataProvider
     */
    public function testMatchDate($subject)
    {
        preg_match(CHAOS_MATCH_DATE, $subject, $types);
        $this->assertNotEmpty($types);
        $this->writeln($types, __FUNCTION__);
    }

    public function testMatchDateDataProvider()
    {
        return [
            ['2017-3-1'],
            ['2017/03/01'],
            ['2017.03.1'],
            ['2017-3-01'],
            ['1-3-2017'],
            ['01/03/2017'],
            ['1.03.2017'],
            ['01-3-2017']
        ];
    }

    /**
     * @dataProvider testMatchAscDescDataProvider
     */
    public function testMatchAscDesc($subject)
    {
        preg_match(CHAOS_MATCH_ASC_DESC, $subject, $types);
        $this->assertNotEmpty($types);
        $this->writeln($types, __FUNCTION__);
    }

    public function testMatchAscDescDataProvider()
    {
        return [
            ['Id'],
            ['Table.Id'],
            ['Id ASC'],
            ['Table.Id DESC'],
            ['Id DESC NULLS FIRST'],
            ['Name ASC NULLS LAST']
        ];
    }

    /**
     * @dataProvider testMatchColumnDataProvider
     */
    public function testMatchColumn($subject)
    {
        preg_match(CHAOS_MATCH_COLUMN, $subject, $types);
        $this->assertNotEmpty($types);
        $this->writeln($types, __FUNCTION__);
    }

    public function testMatchColumnDataProvider()
    {
        return [
            ['@Column(type="string", length=32, unique=true, nullable=false)'],
            ['@Column(type="string", columnDefinition="CHAR(2) NOT NULL")'],
            ['@Column(type="decimal", precision=2, scale=1)'],
            ['@Column(type="string", length=2, options={"fixed":true, "comment":"Initial letters of first and last name"})'],
            ['@Column(type="integer", name="login_count" nullable=false, options={"unsigned":true, "default":0})'],
            ['@Column(type="boolean")'],
            ['@Column(type="string", columnDefinition="ENUM(\'visible\', \'invisible\')")'],
            ['@Column(type="string", columnDefinition="CHAR(2) NOT NULL")']
        ];
    }

    /**
     * @dataProvider testMatchOneManyDataProvider
     */
    public function testMatchOneMany($subject)
    {
        preg_match(CHAOS_MATCH_ONE_MANY, $subject, $types);
        $this->assertNotEmpty($types);
        $this->writeln($types, __FUNCTION__);
    }

    public function testMatchOneManyDataProvider()
    {
        return [
            ['@OneToOne(targetEntity="Customer")'],
            ['@OneToMany(targetEntity="Phonenumber", mappedBy="user", cascade={"persist", "remove", "merge"}, orphanRemoval=true)'],
            ['@ManyToMany(targetEntity="Group", inversedBy="features")'],
            ['@ManyToOne(targetEntity="Cart", cascade={"all"}, fetch="EAGER")']
        ];
    }

    /**
     * @dataProvider matchTypeDataProvider
     */
    public function testMatchType($subject)
    {
        preg_match(CHAOS_MATCH_TYPE, $subject, $types);
        $this->assertNotEmpty($types);
        $this->writeln($types, __FUNCTION__);
    }

    public function matchTypeDataProvider()
    {
        return [
            ['@Type("\T")'],
            ['@Type("array<\T>")'],
            ['@Type("array<\K, \V>")'],
            ['@Type("\Array\Collection")'],
            ['@Type("\Array\Collection<\T>")'],
            ['@Type("\Array\Collection<\K, \V>")'],
            ['@Type("DateTime<\'Y-m-d\'>")'],
            ['@Type("DateTime<\'Y-m-d\', \'UTC\'>")'],
            ['@Type("DateTime<\'Y-m-d H:i:sP (e)\', \'America/New_York\', \'Y/m.d\TH:i:s.u\'>")'],
            ['/** @Type("DateTime<\'Y-m-d H:i:sP (e)\', \'America/New_York\', \'Y/m.d\TH:i:s.u\'>") */'],
            ['/**
               * @Type("DateTime<\'Y-m-d H:i:sP (e)\', \'America/New_York\', \'Y/m.d\TH:i:s.u\'>")
               */']
        ];
    }

    /**
     * @dataProvider testMatchVarDataProvider
     */
    public function testMatchVar($subject)
    {
        preg_match(CHAOS_MATCH_VAR, $subject, $types);
        $this->assertNotEmpty($types);
        $this->writeln($types, __FUNCTION__);
    }

    public function testMatchVarDataProvider()
    {
        return [
            ['@var \T'],
            ['@var \T[]'],
            ['@var array<\T>'],
            ['@var \Array\Collection<\T>'],
            ['/** @var \Array\Collection<\T> */'],
            ['/**
               * @var \Array\Collection<\T>
               */'],
            // format cases
            ['@var  T '],
            ['@var  \T ']
        ];
    }

    // </editor-fold>

    // <editor-fold desc="functions.php" defaultstate="collapsed">

    public function testIsBlank()
    {
        $this->assertTrue(isBlank(null));
        $this->assertTrue(isBlank(''));
        $this->assertTrue(isBlank(" \t\v\x0B\n\r\f"));
        $this->assertFalse(isBlank([]));
    }

    public function testIsJson()
    {
        $json = '{"success":true,"data":{"Id":"2017A   1158 AA 54","Agreement":{"Created":"2015-12-17","Cedent":null}}}';
        $this->assertNotFalse(isJson($json));
        $this->assertNotFalse(isJson($json, false));
        $this->assertNotFalse(isJson($json, false, 512));
        $this->assertNotFalse(isJson($json, false, 512, JSON_BIGINT_AS_STRING));
        $this->assertFalse(isJson('{"success":true,'));
    }

    public function testGuessNamespace()
    {
        $this->assertEquals('Chaos\Tests\FunctionTest', guessNamespace('Chaos\Tests\FunctionTest'));
        $this->assertEquals('Chaos\Tests\FunctionTest', guessNamespace('FunctionTest'));
        $this->assertEquals('\ClassNotExist', guessNamespace('ClassNotExist'));
    }

    public function testReflect()
    {
        $this->assertInstanceOf('ReflectionClass', reflect('Chaos\Tests\FunctionTest'));
    }

    public function testShorten()
    {
        $this->assertEquals('FunctionTest', shorten('Chaos\Tests\FunctionTest'));
    }

    // </editor-fold>

    /**
     * @param   mixed $value
     * @param   string $caller
     */
    private function writeln($value, $caller = __FUNCTION__)
    {
        echo $caller . ': ' . stripslashes(json_encode($value)) . PHP_EOL;
    }
}
