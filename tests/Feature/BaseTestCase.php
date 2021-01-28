<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\WithCommonMethodsTrait;

class BaseTestCase extends TestCase
{
    use RefreshDatabase, WithFaker, WithCommonMethodsTrait;

    const headers = [
        'Accept' => 'application/json'
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');
        $this->artisan('db:seed --class=RoleSeeder');
    }
}
