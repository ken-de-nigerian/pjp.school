<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::query()->firstOrCreate(
            ['adminId' => 'student-test-admin'],
            [
                'name' => 'Admin',
                'email' => 'studentadmin@test.local',
                'password' => Hash::make('password'),
                'user_type' => 1,
            ]
        );
    }

    public function test_guest_cannot_access_students(): void
    {
        $response = $this->get(route('admin.classes'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_students_index_redirects_to_classes(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.classes'));

        $response->assertOk();
    }

    public function test_admin_can_access_classes_index(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.classes'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.classes.index');
        $response->assertViewHas('classesWithCounts');
    }

    public function test_admin_can_access_create_form(): void
    {
        SchoolClass::query()->create(['class_name' => 'JSS 1', 'time_added' => now()]);

        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.students.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.students.create');
        $response->assertViewHas('classes');
        $response->assertViewHas('nextRegNumber');
    }

    public function test_admin_can_store_student(): void
    {
        SchoolClass::query()->create(['class_name' => 'JSS 1', 'time_added' => now()]);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.students.store'), [
            'reg_number' => '1001',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'othername' => 'None',
            'dob' => '2010-01-01',
            'gender' => 'Male',
            'class' => 'JSS 1',
            'subjects' => 'Math,English',
            'house' => null,
            'category' => 'Day',
            'contact_phone' => '08012345678',
            'lga' => 'Lagos',
            'state' => 'Lagos',
            'city' => 'Lagos',
            'nationality' => 'Nigerian',
            'address' => '123 Street',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('students', [
            'reg_number' => '1001',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'class' => 'JSS 1',
            'status' => 2,
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        SchoolClass::query()->create(['class_name' => 'JSS 1', 'time_added' => now()]);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.students.store'), [
            'reg_number' => '',
            'firstname' => '',
            'lastname' => '',
            'othername' => 'None',
            'dob' => '',
            'class' => '',
            'subjects' => '',
            'contact_phone' => '',
            'lga' => '',
            'state' => '',
            'city' => '',
            'nationality' => '',
            'address' => '',
            '_token' => csrf_token(),
        ]);

        $response->assertSessionHasErrors(['reg_number', 'firstname', 'lastname', 'class', 'subjects', 'contact_phone', 'lga', 'state', 'city', 'nationality', 'address', 'dob']);
    }

    public function test_store_rejects_duplicate_reg_number(): void
    {
        SchoolClass::query()->create(['class_name' => 'JSS 1', 'time_added' => now()]);
        Student::query()->create([
            'reg_number' => '1001',
            'firstname' => 'A',
            'lastname' => 'B',
            'class' => 'JSS 1',
            'status' => 2,
        ]);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.students.store'), [
            'reg_number' => '1001',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'othername' => 'None',
            'dob' => '2010-01-01',
            'class' => 'JSS 1',
            'subjects' => 'Math',
            'contact_phone' => '08012345678',
            'lga' => 'Lagos',
            'state' => 'Lagos',
            'city' => 'Lagos',
            'nationality' => 'Nigerian',
            'address' => '123 Street',
            '_token' => csrf_token(),
        ]);

        $response->assertSessionHasErrors(['reg_number']);
    }

    public function test_admin_can_view_student_show(): void
    {
        $student = Student::query()->create([
            'reg_number' => '1001',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'class' => 'JSS 1',
            'status' => 2,
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.students.show', $student));

        $response->assertStatus(200);
        $response->assertViewIs('admin.students.show');
        $response->assertViewHas('student', $student);
    }

    public function test_show_returns_404_for_missing_student(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.students.show', 99999));

        $response->assertStatus(404);
    }

    public function test_admin_can_update_student_account(): void
    {
        $student = Student::query()->create([
            'reg_number' => '1001',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'class' => 'JSS 1',
            'status' => 2,
        ]);

        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.students.update.account', $student), [
            'firstname' => 'Jane',
            'lastname' => 'Doe',
            'othername' => 'M',
            'dob' => '2010-01-01',
            'gender' => 'Female',
            'contact_phone' => '08012345678',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('admin.students.edit', $student));
        $student->refresh();
        $this->assertSame('Jane', $student->firstname);
    }

    public function test_admin_can_delete_student(): void
    {
        $student = Student::query()->create([
            'reg_number' => '1001',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'class' => 'JSS 1',
            'status' => 2,
        ]);

        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.students.destroy', $student));

        $response->assertRedirect(route('admin.classes'));
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    public function test_add_class_creates_class(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.add.class'), [
            'class_name' => 'SS 3',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('classes', ['class_name' => 'SS 3']);
    }

    public function test_add_class_rejects_duplicate(): void
    {
        SchoolClass::query()->create(['class_name' => 'JSS 1', 'time_added' => now()]);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.add.class'), [
            'class_name' => 'JSS 1',
            '_token' => csrf_token(),
        ]);

        $response->assertSessionHasErrors(['class_name']);
    }
}
