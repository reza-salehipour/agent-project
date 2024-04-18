<?php

namespace Tests\Feature;

use App\Http\Controllers\AgentController;
use App\Models\Agent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Testing\File;
use Tests\TestCase;

class AgentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_main_screen_can_be_rendered(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_excel_file_is_only_csv(): void
    {
        $file = File::create('report.pdf', 100);
        $response = $this->post('/upload', [
            'excel' => $file,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('excel');
    }

    public function test_excel_file_is_required(): void
    {
        $response = $this->post('/upload');

        $response->assertStatus(302);
        $response->assertSessionHasErrors('excel');
    }

    public function test_if_setName_function_is_instance_of_agent(): void
    {
        $controller = new AgentController();
        $this->assertInstanceOf(Agent::class, $controller->SetNames('Mrs', 'Jane', null, 'Smith'));
    }

    public function test_if_extractNames_function_is_instance_of_agent(): void
    {
        $controller = new AgentController();
        $testArray[]='Mr';
        $this->assertInstanceOf(Agent::class, $controller->extractNames($testArray,'Smith'));
    }
}
