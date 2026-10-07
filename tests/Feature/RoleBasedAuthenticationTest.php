<?php

namespace Tests\Feature;

use App\Models\BiomedicalServiceRequest;
use App\Models\EngineerProfile;
use App\Models\Equipment;
use App\Models\Facility;
use App\Models\ServiceReport;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleBasedAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_selection_and_facility_registration_pages_render(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertViewIs('Auth.login');

        $this->get(route('register'))
            ->assertOk()
            ->assertViewIs('Auth.HealthFacilityCreateLogin');
    }

    public function test_facility_service_request_form_renders(): void
    {
        /** @var User $facility */
        $facility = User::factory()->create(['role' => 'facility']);

        $this->actingAs($facility)
            ->get(route('facility.service-requests.create'))
            ->assertOk()
            ->assertViewIs('HealthyFacility.create')
            ->assertSee('Horizon Lab')
            ->assertSee('alt="Horizon Lab logo"', false);
    }

    public function test_facility_equipment_placeholder_renders(): void
    {
        /** @var User $facility */
        $facility = User::factory()->create(['role' => 'facility']);

        $this->actingAs($facility)
            ->get(route('facility.equipment.index'))
            ->assertOk()
            ->assertViewIs('HealthyFacility.comingsoon');
    }

    public function test_facility_and_engineer_login_pages_are_separate(): void
    {
        $this->get(route('facility.login'))
            ->assertOk()
            ->assertViewIs('Auth.HealthfacilityLogin');

        $this->get(route('engineer.login'))
            ->assertOk()
            ->assertViewIs('Auth.engineer-login');
    }

    public function test_facility_login_redirects_to_facility_dashboard(): void
    {
        $facility = User::factory()->create([
            'email' => 'facility@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'facility',
        ]);

        $response = $this->post(route('facility.login.submit'), [
            'email' => 'facility@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirectToRoute('facility.dashboard');
        $this->assertAuthenticatedAs($facility);
    }

    public function test_engineer_login_redirects_to_engineer_dashboard(): void
    {
        $engineer = User::factory()->create([
            'email' => 'engineer@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'engineer',
        ]);

        $response = $this->post(route('engineer.login.submit'), [
            'email' => 'engineer@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirectToRoute('engineer.dashboard');
        $this->assertAuthenticatedAs($engineer);
    }

    public function test_assigned_engineer_can_open_service_report_form(): void
    {
        /** @var User $engineer */
        $engineer = User::factory()->create(['role' => 'engineer']);
        /** @var User $facilityUser */
        $facilityUser = User::factory()->create(['role' => 'facility']);
        $facility = Facility::create([
            'user_id' => $facilityUser->id,
            'name' => 'Report Form Hospital',
            'address' => 'Kampala',
        ]);
        $serviceRequest = BiomedicalServiceRequest::create([
            'facility_id' => $facility->id,
            'engineer_id' => $engineer->id,
            'equipment_name' => 'Ultrasound machine',
            'service_type' => 'repair',
            'urgency' => 'normal',
            'issue_description' => 'The display is not working.',
            'status' => 'assigned',
        ]);

        $this->actingAs($engineer)
            ->get(route('engineer.reports.create', $serviceRequest))
            ->assertOk()
            ->assertViewIs('Engineer.CreateEngineer')
            ->assertSee('Ultrasound machine')
            ->assertSee('Problem found');
    }

    public function test_engineer_dashboard_shows_own_assignments_facilities_and_reports(): void
    {
        /** @var User $engineer */
        $engineer = User::factory()->create(['role' => 'engineer']);
        /** @var User $otherEngineer */
        $otherEngineer = User::factory()->create(['role' => 'engineer']);
        /** @var User $facilityUser */
        $facilityUser = User::factory()->create(['role' => 'facility']);
        $facility = Facility::create([
            'user_id' => $facilityUser->id,
            'name' => 'Assigned Engineer Hospital',
            'address' => 'Kampala',
            'district' => 'Central',
        ]);
        $assignedRequest = BiomedicalServiceRequest::create([
            'facility_id' => $facility->id,
            'engineer_id' => $engineer->id,
            'equipment_name' => 'Assigned Ultrasound',
            'service_type' => 'repair',
            'urgency' => 'high',
            'issue_description' => 'Display needs service.',
            'status' => 'in_progress',
        ]);
        $otherRequest = BiomedicalServiceRequest::create([
            'facility_id' => $facility->id,
            'engineer_id' => $otherEngineer->id,
            'equipment_name' => 'Other Engineer Equipment',
            'service_type' => 'repair',
            'urgency' => 'normal',
            'issue_description' => 'Not this engineer\'s request.',
            'status' => 'completed',
        ]);
        ServiceReport::create([
            'biomedical_service_request_id' => $assignedRequest->id,
            'engineer_id' => $engineer->id,
            'problem_found' => 'Engineer-owned report finding.',
            'work_done' => 'Repair completed.',
            'labor_hours' => 2,
            'hourly_rate' => 50000,
            'labor_cost' => 100000,
            'parts_cost' => 0,
            'total_cost' => 100000,
        ]);
        ServiceReport::create([
            'biomedical_service_request_id' => $otherRequest->id,
            'engineer_id' => $otherEngineer->id,
            'problem_found' => 'Another engineer\'s private report finding.',
            'work_done' => 'Unrelated work.',
            'labor_hours' => 1,
            'hourly_rate' => 20000,
            'labor_cost' => 20000,
            'parts_cost' => 0,
            'total_cost' => 20000,
        ]);

        $this->actingAs($engineer)
            ->get(route('engineer.dashboard'))
            ->assertOk()
            ->assertSee('href="'.route('engineer.dashboard').'#assignments"', false)
            ->assertSee('href="'.route('engineer.dashboard').'#assigned-facilities"', false)
            ->assertSee('href="'.route('engineer.dashboard').'#reports"', false)
            ->assertSee('Assigned Ultrasound')
            ->assertSee('Assigned Engineer Hospital')
            ->assertSee('Central')
            ->assertSee('Engineer-owned report finding.')
            ->assertSee('href="#assignments"', false)
            ->assertSee('href="#assigned-facilities"', false)
            ->assertSee('href="#reports"', false)
            ->assertDontSee('Other Engineer Equipment')
            ->assertDontSee('Another engineer\'s private report finding.');
    }

    public function test_all_role_dashboards_render_search_and_role_scoped_notifications(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        /** @var User $facilityUser */
        $facilityUser = User::factory()->create(['role' => 'facility']);
        /** @var User $engineer */
        $engineer = User::factory()->create(['role' => 'engineer']);
        $facility = Facility::create([
            'user_id' => $facilityUser->id,
            'name' => 'Dashboard Search Hospital',
            'address' => 'Kampala',
        ]);

        $assignedRequest = BiomedicalServiceRequest::create([
            'facility_id' => $facility->id,
            'engineer_id' => $engineer->id,
            'equipment_name' => 'Assigned Search Equipment',
            'service_type' => 'repair',
            'urgency' => 'high',
            'issue_description' => 'Assigned request for dashboard search.',
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);
        $pendingRequest = BiomedicalServiceRequest::create([
            'facility_id' => $facility->id,
            'equipment_name' => 'Pending Search Equipment',
            'service_type' => 'repair',
            'urgency' => 'normal',
            'issue_description' => 'Pending request notification for admin.',
            'status' => 'pending',
        ]);

        $this->actingAs($facilityUser)
            ->get(route('facility.dashboard'))
            ->assertOk()
            ->assertSee('data-dashboard-search', false)
            ->assertSee('data-dashboard-notifications', false)
            ->assertSee('data-notification-id="facility-request-'.$assignedRequest->id.'"', false)
            ->assertSee('data-dashboard-searchable', false);

        $this->actingAs($engineer)
            ->get(route('engineer.dashboard'))
            ->assertOk()
            ->assertSee('data-dashboard-search', false)
            ->assertSee('data-dashboard-notifications', false)
            ->assertSee('data-notification-id="engineer-assignment-'.$assignedRequest->id.'"', false)
            ->assertSee('data-dashboard-searchable', false);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('data-dashboard-search', false)
            ->assertSee('data-dashboard-notifications', false)
            ->assertSee('data-notification-id="request-'.md5(route('admin.biomedical-service-requests.assign', $pendingRequest)).'"', false)
            ->assertSee('data-dashboard-searchable', false);
    }

    public function test_engineer_cannot_sign_in_through_facility_login(): void
    {
        User::factory()->create([
            'email' => 'engineer@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'engineer',
        ]);

        $response = $this->from(route('facility.login'))->post(route('facility.login.submit'), [
            'email' => 'engineer@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('facility.login'));
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_facility_cannot_sign_in_through_engineer_login(): void
    {
        User::factory()->create([
            'email' => 'facility@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'facility',
        ]);

        $response = $this->from(route('engineer.login'))->post(route('engineer.login.submit'), [
            'email' => 'facility@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('engineer.login'));
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_engineer_registration_creates_an_engineer_account_and_profile(): void
    {
        $response = $this->post(route('engineer-register.submit'), [
            'name' => 'Engineer Example',
            'email' => 'engineer@example.com',
            'professional_title' => 'Biomedical Engineer',
            'specialization' => 'Imaging equipment',
            'license_number' => 'ENG-12345',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirectToRoute('engineer.dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'engineer@example.com',
            'role' => 'engineer',
        ]);
        $this->assertDatabaseHas('engineer_profiles', [
            'license_number' => 'ENG-12345',
        ]);
        $this->assertAuthenticated();
    }

    public function test_admin_can_login_and_approve_pending_engineer(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
        ]);

        $engineer = User::factory()->create([
            'email' => 'pending-engineer@example.com',
            'role' => 'engineer',
        ]);

        EngineerProfile::create([
            'user_id' => $engineer->id,
            'professional_title' => 'Biomedical Engineer',
            'specialization' => 'Imaging',
            'license_number' => 'ENG-PENDING-1',
            'is_approved' => false,
            'is_available' => true,
        ]);

        $this->post(route('admin.login.submit'), [
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ])->assertRedirectToRoute('admin.dashboard');

        $this->actingAs($admin)
            ->post(route('admin.engineers.approve', $engineer->id))
            ->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('engineer_profiles', [
            'user_id' => $engineer->id,
            'is_approved' => true,
        ]);
    }

    public function test_admin_management_sections_render_for_admins(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);

        foreach ([
            'admin.engineers.index',
            'admin.service-requests.index',
            'admin.facilities.index',
            'admin.assignments.index',
            'admin.reports.index',
            'admin.users.index',
        ] as $routeName) {
            $this->actingAs($admin)
                ->get(route($routeName))
                ->assertOk()
                ->assertViewIs('admin.section');
        }

        $this->actingAs($admin)
            ->get(route('admin.settings.index'))
            ->assertOk()
            ->assertViewIs('admin.settings')
            ->assertSee($admin->email);

        $dashboard = $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();

        foreach ([
            'admin.engineers.index',
            'admin.service-requests.index',
            'admin.facilities.index',
            'admin.assignments.index',
            'admin.reports.index',
            'admin.users.index',
            'admin.settings.index',
        ] as $routeName) {
            $dashboard->assertSee(route($routeName), false);
        }
    }

    public function test_admin_lists_show_records_from_the_database(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        /** @var User $facilityUser */
        $facilityUser = User::factory()->create(['role' => 'facility']);
        $facility = Facility::create([
            'user_id' => $facilityUser->id,
            'name' => 'Central City Hospital',
            'address' => 'Kampala',
        ]);
        $engineerUser = User::factory()->create([
            'name' => 'Morgan Engineer',
            'email' => 'morgan.engineer@example.com',
            'role' => 'engineer',
        ]);
        $engineer = EngineerProfile::create([
            'user_id' => $engineerUser->id,
            'specialization' => 'Imaging systems',
            'license_number' => 'ENG-LIVE-1',
            'is_approved' => true,
            'is_available' => true,
        ]);
        $equipment = Equipment::create([
            'facility_id' => $facility->id,
            'name' => 'CT Scanner',
            'manufacturer' => 'Horizon Medical',
            'model' => 'CT-1',
            'serial_number' => 'CT-LIVE-1',
            'location_label' => 'Imaging department',
            'oem_verified' => true,
        ]);
        ServiceRequest::create([
            'form_ref' => 'HL-LIVE-1001',
            'facility_id' => $facility->id,
            'equipment_id' => $equipment->id,
            'requested_by' => $facilityUser->id,
            'problem_classification' => 'Scanner image quality issue',
            'diagnostic_notes' => 'Image is blurred',
            'urgency_tier' => 'high',
            'sla_minutes' => 60,
            'status' => 'assigned',
            'assigned_engineer_profile_id' => $engineer->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.engineers.index'))
            ->assertOk()
            ->assertSee('morgan.engineer@example.com')
            ->assertSee('ENG-LIVE-1');

        $this->get(route('admin.service-requests.index'))
            ->assertOk()
            ->assertSee('HL-LIVE-1001')
            ->assertSee('Morgan Engineer');

        $this->get(route('admin.assignments.index'))
            ->assertOk()
            ->assertSee('HL-LIVE-1001');

        $this->get(route('admin.facilities.index'))
            ->assertOk()
            ->assertSee('Central City Hospital')
            ->assertSee('CT Scanner');
    }

    public function test_admin_can_view_a_user_account_but_other_roles_cannot(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        $facilityUser = User::factory()->create([
            'name' => 'Facility Account Owner',
            'email' => 'facility-owner@example.com',
            'role' => 'facility',
        ]);
        Facility::create([
            'user_id' => $facilityUser->id,
            'name' => 'Account Detail Hospital',
            'address' => 'Kampala',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.show', $facilityUser))
            ->assertOk()
            ->assertViewIs('admin.user-account')
            ->assertSee('facility-owner@example.com')
            ->assertSee('Account Detail Hospital');

        $this->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee(route('admin.users.show', $facilityUser), false);

        $engineerUser = User::factory()->create(['role' => 'engineer']);
        EngineerProfile::create([
            'user_id' => $engineerUser->id,
            'professional_title' => 'Biomedical Engineer',
            'specialization' => 'Imaging systems',
            'license_number' => 'ENG-ACCOUNT-1',
            'is_approved' => true,
            'is_available' => true,
        ]);

        $this->get(route('admin.users.show', $engineerUser))
            ->assertOk()
            ->assertSee('Imaging systems')
            ->assertSee('ENG-ACCOUNT-1')
            ->assertSee('Approved');

        /** @var User $nonAdmin */
        $nonAdmin = User::factory()->create(['role' => 'engineer']);

        $this->actingAs($nonAdmin)
            ->get(route('admin.users.show', $facilityUser))
            ->assertForbidden();
    }

    public function test_admin_can_update_own_account_settings(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create([
            'name' => 'Dashboard Admin',
            'email' => 'dashboard-admin@example.com',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'name' => 'Horizon Administrator',
                'email' => 'administrator@example.com',
            ])
            ->assertRedirectToRoute('admin.settings.index')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Horizon Administrator',
            'email' => 'administrator@example.com',
        ]);
    }

    public function test_admin_can_assign_approved_engineer_to_hospital_request(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create([
            'email' => 'admin2@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
        ]);

        $facilityUser = User::factory()->create(['role' => 'facility']);
        $facility = Facility::create([
            'user_id' => $facilityUser->id,
            'name' => 'Kampala General Hospital',
            'address' => 'Kampala',
        ]);

        $engineerUser = User::factory()->create(['role' => 'engineer']);
        $engineerProfile = EngineerProfile::create([
            'user_id' => $engineerUser->id,
            'professional_title' => 'Field Engineer',
            'specialization' => 'Ventilator',
            'license_number' => 'ENG-ASSIGN-1',
            'is_approved' => true,
            'is_available' => true,
            'facility_id' => $facility->id,
        ]);

        $equipment = Equipment::create([
            'facility_id' => $facility->id,
            'name' => 'Ventilator',
            'manufacturer' => 'Resmed',
            'model' => 'V100',
            'serial_number' => 'SER-001',
            'location_label' => 'Ward A',
            'oem_verified' => true,
        ]);

        $request = ServiceRequest::create([
            'form_ref' => 'HL-REQ-1001',
            'facility_id' => $facility->id,
            'equipment_id' => $equipment->id,
            'requested_by' => $facilityUser->id,
            'problem_classification' => 'Ventilator not heating',
            'diagnostic_notes' => 'Needs inspection',
            'urgency_tier' => 'high',
            'sla_minutes' => 45,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.service-requests.assign', $request->id), [
                'engineer_profile_id' => $engineerProfile->id,
            ])
            ->assertRedirect(route('admin.dashboard'));

        $request->refresh();
        $this->assertSame($engineerProfile->id, $request->assigned_engineer_profile_id);
        $this->assertSame('assigned', $request->status);
    }

    public function test_facility_service_request_appears_on_admin_dashboard_and_can_be_assigned(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        /** @var User $facilityUser */
        $facilityUser = User::factory()->create(['role' => 'facility']);
        $facility = Facility::create([
            'user_id' => $facilityUser->id,
            'name' => 'Request Visibility Hospital',
            'address' => 'Kampala',
        ]);
        $engineerUser = User::factory()->create(['role' => 'engineer']);
        $engineerProfile = EngineerProfile::create([
            'user_id' => $engineerUser->id,
            'specialization' => 'Radiology equipment',
            'license_number' => 'ENG-VISIBLE-1',
            'is_approved' => true,
            'is_available' => true,
        ]);

        $this->actingAs($facilityUser)
            ->post(route('facility.service-requests.store'), [
                'equipment_name' => 'Portable X-ray',
                'service_type' => 'repair',
                'urgency' => 'critical',
                'issue_description' => 'The unit will not power on.',
            ])
            ->assertRedirectToRoute('facility.service-requests.create')
            ->assertSessionHas('status');

        $serviceRequest = BiomedicalServiceRequest::query()->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Notifications')
            ->assertSee('data-notification-id', false)
            ->assertSee('data-sidebar-notification="service-request"', false)
            ->assertSee('New service request')
            ->assertSee('Portable X-ray')
            ->assertSee('The unit will not power on.');

        $this->post(route('admin.biomedical-service-requests.assign', $serviceRequest), [
            'engineer_profile_id' => $engineerProfile->id,
        ])->assertRedirectToRoute('admin.dashboard');

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee($engineerUser->name)
            ->assertSee('Assigned');

        $this->get(route('admin.service-requests.index'))
            ->assertOk()
            ->assertSee('Portable X-ray')
            ->assertSee($engineerUser->name);

        $this->get(route('admin.assignments.index'))
            ->assertOk()
            ->assertSee('Portable X-ray')
            ->assertSee($engineerUser->name);

        $this->get(route('admin.facilities.index'))
            ->assertOk()
            ->assertSee('Request Visibility Hospital');

        $this->assertDatabaseHas('biomedical_service_requests', [
            'id' => $serviceRequest->id,
            'engineer_id' => $engineerUser->id,
            'status' => 'assigned',
        ]);
    }
}
