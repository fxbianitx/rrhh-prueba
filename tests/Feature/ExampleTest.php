<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_redirige_al_listado_de_empleados(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('employees.index'));
    }
}
