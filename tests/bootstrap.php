<?php
require __DIR__ . '/../vendor/autoload.php';

/** @noinspection PhpIncludeInspection */
require dirname((new ReflectionClass(PHPUnit\Framework\Assert::class))->getFileName()) . '/Assert/Functions.php';

class PHPUnit_Framework_TestCase extends PHPUnit\Framework\TestCase {}
