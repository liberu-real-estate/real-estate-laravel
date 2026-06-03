<?php

namespace Database\Seeders;

use App\Models\Team;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Permissions assigned per role (wildcard suffixes matched against permission names).
     * Each entry is a list of substrings — a permission is granted when its name contains
     * any of the listed substrings.
     */
    protected array $rolePermissions = [
        'staff' => [
            'view', 'create', 'update',
            // Staff can manage most content but not delete
        ],
        'agent' => [
            '_property', '_appointment', '_booking', '_lead',
            '_market_analysis', '_agent_performance', '_favorite',
            '_communication_hub', '_chat_conversation', '_review',
            '_document', '_image', '_email_campaign',
        ],
        'buyer' => [
            'view_property', 'view_any_property',
            'view_booking', 'create_booking', 'update_booking',
            'view_favorite', 'create_favorite', 'delete_favorite',
            'view_any_favorite', 'delete_any_favorite',
            'view_review', 'create_review',
            'view_communication_hub', 'create_communication_hub',
        ],
        'seller' => [
            'view_property', 'view_any_property', 'create_property', 'update_property',
            'view_document', 'view_any_document', 'create_document',
            'view_appointment', 'view_any_appointment',
            'view_review', 'view_any_review',
            'view_image', 'create_image', 'update_image', 'delete_image',
            'view_communication_hub', 'create_communication_hub',
        ],
        'landlord' => [
            'view_property', 'view_any_property', 'create_property', 'update_property',
            'view_lease', 'view_any_lease', 'create_lease', 'update_lease',
            'view_lease_agreement', 'view_any_lease_agreement', 'create_lease_agreement',
            'view_rental_application', 'view_any_rental_application', 'update_rental_application',
            'view_tenant', 'view_any_tenant',
            'view_maintenance_request', 'view_any_maintenance_request', 'update_maintenance_request',
            'view_utility_payment', 'view_any_utility_payment',
            'view_transaction', 'view_any_transaction',
            'view_document', 'create_document', 'view_any_document',
            'view_communication_hub', 'create_communication_hub',
        ],
        'tenant' => [
            'view_property', 'view_any_property',
            'view_lease', 'view_any_lease',
            'view_lease_agreement', 'view_any_lease_agreement',
            'view_maintenance_request', 'view_any_maintenance_request',
            'create_maintenance_request', 'update_maintenance_request',
            'view_payment', 'view_any_payment', 'create_payment',
            'view_utility_payment', 'view_any_utility_payment',
            'view_communication_hub', 'create_communication_hub',
            'view_review', 'create_review',
        ],
        'contractor' => [
            'view_contractor', 'view_any_contractor', 'update_contractor',
            'view_maintenance_request', 'view_any_maintenance_request', 'update_maintenance_request',
            'view_document', 'view_any_document',
            'view_communication_hub', 'create_communication_hub',
        ],
    ];

    public function run(): void
    {
        $teamId = null;
        if (Utils::isTenancyEnabled()) {
            $team = Team::firstOrFail();
            $teamId = $team->id;
        }

        $allPermissions = Permission::where('guard_name', 'web')->pluck('id', 'name');

        // Privileged roles get all permissions
        foreach (['super_admin', 'admin'] as $roleName) {
            $role = $this->findOrCreateRole($roleName, $teamId);
            $role->syncPermissions($allPermissions->values()->toArray());
        }

        // Staff gets all view/create/update permissions (no deletes)
        $staffRole = $this->findOrCreateRole('staff', $teamId);
        $staffPermissions = $allPermissions->filter(
            fn ($id, $name) => str_starts_with($name, 'view')
                || str_starts_with($name, 'create')
                || str_starts_with($name, 'update')
        )->values()->toArray();
        $staffRole->syncPermissions($staffPermissions);

        // Agent: property, appointment, booking, lead management
        $agentRole = $this->findOrCreateRole('agent', $teamId);
        $agentRole->syncPermissions(
            $this->resolvePermissions($allPermissions, $this->rolePermissions['agent'])
        );

        // Role-specific permissions for remaining roles
        foreach (['buyer', 'seller', 'landlord', 'tenant', 'contractor'] as $roleName) {
            $role = $this->findOrCreateRole($roleName, $teamId);
            $role->syncPermissions(
                $this->resolvePermissions($allPermissions, $this->rolePermissions[$roleName])
            );
        }
    }

    protected function findOrCreateRole(string $name, ?int $teamId): Role
    {
        $data = ['name' => $name, 'guard_name' => 'web'];
        if ($teamId) {
            $data['team_id'] = $teamId;
        }
        return Role::firstOrCreate($data);
    }

    protected function resolvePermissions(\Illuminate\Support\Collection $allPermissions, array $patterns): array
    {
        return $allPermissions->filter(function ($id, $name) use ($patterns) {
            foreach ($patterns as $pattern) {
                if (str_contains($name, $pattern)) {
                    return true;
                }
            }
            return false;
        })->values()->toArray();
    }
}
