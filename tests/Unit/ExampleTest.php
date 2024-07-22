<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Test basic string operations.
     */
    public function test_string_operations(): void
    {
        $string = "Hello, World!";
        $this->assertEquals(13, strlen($string));
        $this->assertEquals("HELLO, WORLD!", strtoupper($string));
        $this->assertTrue(str_contains($string, "World"));
    }

    /**
     * Test basic array operations.
     */
    public function test_array_operations(): void
    {
        $array = [1, 2, 3, 4, 5];
        $this->assertEquals(5, count($array));
        $this->assertTrue(in_array(3, $array));
        $this->assertEquals([2, 3, 4], array_slice($array, 1, 3));
    }
}
