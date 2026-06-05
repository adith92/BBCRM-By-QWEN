<?php

use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class and a function name. By default, that's illuminated by PHPUnit observers
| which fire events during test execution — this helps you to extract utilities from
| the underlying test case into helper functions that you may reference anywhere in
| your tests using $this->
|
*/

uses(TestCase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of matchers that let you make various
| assertions against code. Of course, you may also use "assertIsString()" or similar
| functions provided by assert - we just wanted to give you a couple of options that
| work really well with Pest.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

expect()->extend('toBeTwo', function () {
    return $this->toBe(2);
});

expect()->extend('toBeThree', function () {
    return $this->toBe(3);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce noise, and focus on what matters most.
|
*/

function something()
{
    // ..
}
