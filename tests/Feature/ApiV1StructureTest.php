<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiV1StructureTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_api_v1_routes_exist(): void
    {
        // We just want to check if the route is registered and controller is callable.
        // It might return 401 (unauthorized) or 200 (if public), or 500 (if DB error),
        // but 404 would mean route not found.

        $response = $this->getJson('/api/v1/products');

        $this->assertNotEquals(404, $response->status(), 'API V1 Product route should exist');
    }

    public function test_api_v1_address_route_exists(): void
    {
        $response = $this->getJson('/api/v1/addresses');

        $this->assertNotEquals(404, $response->status(), 'API V1 Address route should exist');
    }
}
