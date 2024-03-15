<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\PermissionAndRolesSeeder;
use Database\Seeders\UsersSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }

    protected function afterRefreshingDatabase()
    {
        $this->seed(PermissionAndRolesSeeder::class);
        $this->seed(UsersSeeder::class);
    }
    public function test_user_registration_with_valid_data()
    {

        $password = $this->faker->password(8);
        $email = fake()->safeEmail();

        $response = $this->post(route('register'), [
            'name' => fake()->name(),
            'surname' => fake()->lastName(),
            'email' => $email,
            'phone' => fake()->e164PhoneNumber(),
            'birthday' => $this->faker->date('Y-m-d', '-30 years'),
            'password' => $password,
            'password_confirmation' => $password
        ]);

        $response->assertStatus(302); // Перевіряємо редірект
        $response->assertRedirect('/home'); // Перевіряємо редірект на сторінку dashboard
        $this->assertAuthenticated(); // Перевіряємо, що користувач автентифікований
        $this->assertDatabaseHas('users', ['email' => $email]); // Перевіряємо наявність користувача в базі даних
    }

}


