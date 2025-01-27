<?php

use PHPUnit\Framework\TestCase;
use Utils\Validator;

class ValidatorTest extends TestCase
{
    public function testEmails()
    {
        $error = Validator::validate([
            'anna.anisimova@example.com', 
            'ivan.ivanov@example.com'
        ], [
            '^[A-Za-z0-9\.]+@[a-z]+\.[a-z]{1,5}$', 
            '^[A-Za-z0-9\.]+@[a-z]+\.[a-z]{1,5}$'
        ], [
            'Incorrect email 1', 
            'Incorrect email 2'
        ]);

        $this->assertEquals('', $error);
    }
    public function testPasswords()
    {
        $error = Validator::validate([
            'qwijrewioalskd', 
            '__#)!+@_!$@()'
        ], [
            '^[A-Za-z0-9\.!@#$%^&*]+$', 
            '^[A-Za-z0-9\.!@#$%^&*]+$'
        ], [
            'Incorrect password 1', 
            'Incorrect password 2'
        ]);

        $this->assertEquals('Incorrect password 2', $error);
    }
}
