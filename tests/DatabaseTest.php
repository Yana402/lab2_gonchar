<?php

use PHPUnit\Framework\TestCase;
use Utils\Database;

Database::connect();

class DatabaseTest extends TestCase
{
    public function testSelectSingle()
    {
        $result = Database::$connection->query('SELECT * FROM users WHERE id = 4');

        $result = $result->fetch_all(MYSQLI_ASSOC);

        $this->assertEquals('anna.anisimova@example.com', $result[0]['email']);
    }
    public function testSelectMultiple()
    {
        $result = Database::$connection->query('SELECT * FROM services');

        $result = $result->fetch_all(MYSQLI_ASSOC);

        $this->assertEquals('Стрижка', $result[0]['name']);
        $this->assertEquals('70', $result[1]['price']);
        $this->assertEquals('Маникюр', $result[2]['name']);
    }
}
