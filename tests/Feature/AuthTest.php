<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    //use RefreshDatabase;

    public function test_it_registers_a_user()
    {  
        
        /**
        *
        * Without Artisan call you will get a passport 
        * "please create a personal access client" error
        */
        Artisan::call('passport:client --personal');
        $response = $this->postJson('/api/v1/auth/register', [
            'name' =>  fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['success', 'message', 'data' => ['user', 'token']]);
    }

 
    public function test_it_fails_to_register_a_user_with_invalid_data()
    {   
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
        ]);

        $response->assertStatus(400)
                 ->assertJsonStructure(['success', 'message', 'errors']);
    }

    public function test_it_logs_in_a_user()
    {   
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'message', 'data' => ['user', 'token']]);
    }

 
    public function test_it_fails_to_log_in_with_invalid_credentials()
    {   
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(401)
                 ->assertJsonStructure(['success', 'message']);
    }


    public function test_it_logs_out_a_user()
    {   
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->accessToken;

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                         ->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'message', 'data']);
    }
}
