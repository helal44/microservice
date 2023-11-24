<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function register_user(){
        $userData=[
            'name'=>'Ali',
            'email'=>'Ali@gmail.com',
            'password'=>'ali1234'
        ];
        $this->json('POST','/register',$userData)
        ->assertStatus(200)->assertJsonStructure(['token']);
    }

  public function login_user(){

    $UserData=[
        'email'=>'Ali@gmail.com',
        'password'=>'ali1234'
    ];

    $this->json('POST', '/login', $UserData)
    ->assertStatus(200)
    ->assertJsonStructure(['token']);
  }
}
