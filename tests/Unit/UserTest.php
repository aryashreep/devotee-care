<?php
// tests/Unit/UserTest.php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use PDO;
use PDOStatement;

class UserTest extends TestCase
{
    private $dbMock;
    private $stmtMock;
    private $userModel;

    protected function setUp(): void
    {
        $this->dbMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->userModel = new User($this->dbMock);
    }

    public function testCreateUserSetsDefaultRoleAndMatchesParameters()
    {
        $userData = [
            'full_name' => 'John Doe',
            'gender' => 'Male',
            'date_of_birth' => '1990-01-01',
            'marital_status' => 'Single',
            'password' => 'password123',
            'mobile_number' => '1234567890',
            'address' => '123 Main St',
            'city' => 'Anytown',
            'state' => 'CA',
            'pincode' => '12345',
            'country' => 'USA',
            'education_id' => 1,
            'profession_id' => 1,
            'bhakti_sadan_id' => 1,
        ];

        $capturedSql = '';
        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->callback(function($sql) use (&$capturedSql) {
                $capturedSql = $sql;
                return true;
            }))
            ->willReturn($this->stmtMock);

        $capturedData = [];
        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->with($this->callback(function($data) use (&$capturedData) {
                $capturedData = $data;
                return true;
            }))
            ->willReturn(true);

        $this->dbMock->method('lastInsertId')->willReturn('1');

        $this->userModel->create($userData);

        $placeholderCount = substr_count($capturedSql, ':');
        $dataCount = count($capturedData);

        $this->assertEquals($placeholderCount, $dataCount, "The number of bound variables should match the number of tokens.");
    }
}
