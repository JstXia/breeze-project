<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPortalFunctionalTest extends TestCase
{

    /**
     * Test 1: Register with valid details
     * Expected Result: Account is created; dashboard opens
     */
    public function test_01_register_with_valid_details(): void
    {
        $response = $this->post('/register', [
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@example.com',
            'password' => 'StudentPortal123!',
            'password_confirmation' => 'StudentPortal123!',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertDatabaseHas('users', ['email' => 'juan@example.com']);
    }

    /**
     * Test 2: Register with an existing email
     * Expected Result: Duplicate email is rejected
     */
    public function test_02_register_with_existing_email(): void
    {
        User::factory()->create([
            'email' => 'juan@example.com',
        ]);

        $response = $this->post('/register', [
            'name' => 'Another Student',
            'email' => 'juan@example.com',
            'password' => 'StudentPortal123!',
            'password_confirmation' => 'StudentPortal123!',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Test 3: Submit empty registration fields
     * Expected Result: Validation messages appear
     */
    public function test_03_submit_empty_registration_fields(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertGuest();
    }

    /**
     * Test 4: Enter different password and confirmation values
     * Expected Result: Registration is rejected
     */
    public function test_04_different_password_and_confirmation(): void
    {
        $response = $this->post('/register', [
            'name' => 'Juan Dela Cruz',
            'email' => 'juan_mismatch@example.com',
            'password' => 'StudentPortal123!',
            'password_confirmation' => 'DifferentPassword123!',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    /**
     * Test 5: Log in with correct credentials
     * Expected Result: Dashboard opens
     */
    public function test_05_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'juan@example.com',
            'password' => bcrypt('StudentPortal123!'),
        ]);

        $response = $this->post('/login', [
            'email' => 'juan@example.com',
            'password' => 'StudentPortal123!',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    /**
     * Test 6: Log in with an incorrect password
     * Expected Result: Login fails
     */
    public function test_06_login_with_incorrect_password(): void
    {
        User::factory()->create([
            'email' => 'juan@example.com',
            'password' => bcrypt('StudentPortal123!'),
        ]);

        $response = $this->post('/login', [
            'email' => 'juan@example.com',
            'password' => 'WrongPassword!',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test 7: Update the profile name
     * Expected Result: Updated name persists after refresh
     */
    public function test_07_update_profile_name(): void
    {
        $user = User::factory()->create([
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@example.com',
        ]);

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Juan M. Dela Cruz',
            'email' => 'juan@example.com',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/profile');

        $user->refresh();
        $this->assertSame('Juan M. Dela Cruz', $user->name);

        $dashResponse = $this->actingAs($user)->get('/dashboard');
        $dashResponse->assertSeeText("Juan M. Dela Cruz's Student Portal", false);
    }

    /**
     * Test 8: Open Student Resources while logged in
     * Expected Result: Page opens
     */
    public function test_08_open_student_resources_while_logged_in(): void
    {
        $user = User::factory()->create([
            'name' => 'Juan Dela Cruz',
        ]);

        $response = $this->actingAs($user)->get('/student-resources');

        $response->assertOk();
        $response->assertSee('Student Resources');
        $response->assertSee('Lesson 1: Introduction to Laravel');
        $response->assertSee('Lesson 6: Eloquent ORM and Database Relationships');
    }

    /**
     * Test 9: Log out, then request /dashboard
     * Expected Result: Redirects to login
     */
    public function test_09_logout_then_request_dashboard(): void
    {
        $user = User::factory()->create();

        $logoutResponse = $this->actingAs($user)->post('/logout');
        $this->assertGuest();
        $logoutResponse->assertRedirect('/');

        $dashResponse = $this->get('/dashboard');
        $dashResponse->assertRedirect(route('login', absolute: false));
    }

    /**
     * Test 10: Request /student-resources in a private window (unauthenticated)
     * Expected Result: Redirects to login
     */
    public function test_10_request_student_resources_unauthenticated(): void
    {
        $response = $this->get('/student-resources');
        $response->assertRedirect(route('login', absolute: false));
    }
}
