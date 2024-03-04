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

    /**
     * A basic feature test example.
     */

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
        $response = $this->post(route('register'), [
            'name' => 'John Doe',
            'surname' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+380675443139',
            'birthday' => '1993-12-20',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertStatus(302); // Перевіряємо редірект
        $response->assertRedirect('/home'); // Перевіряємо редірект на сторінку dashboard
        $this->assertAuthenticated(); // Перевіряємо, що користувач автентифікований
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']); // Перевіряємо наявність користувача в базі даних
    }

    public function test_user_registration_with_invalid_data()
    {
        $response = $this->post(route('register'), [
            'name' => 'John Doe',
            'surname' => 'John Doe',
            'email' => '',
            'phone' => '+380675443139',
            'birthday' => '1993-12-20',
            'password' => 'passwor23',
            'password_confirmation' => 'password123'
        ]);

        $response->assertSessionHasErrors(['email']); // Перевіряємо наявність помилок у сесії
        $response->assertRedirect(); // Перевіряємо редірект на поточну сторінку (бо валідація не пройшла)
        $this->assertGuest(); // Перевіряємо, що користувач не автентифікований
        $this->assertDatabaseMissing('users', ['email' => 'invalid-email']); // Перевіряємо відсутність користувача в базі даних
    }
}


