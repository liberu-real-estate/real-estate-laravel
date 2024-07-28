<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Services\TeamManagementService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Exception;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    protected $teamManagementService;

    public function __construct(TeamManagementService $teamManagementService)
    {
        $this->teamManagementService = $teamManagementService;
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, string> $input
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function create(array $input): User
    {
        try {
            Validator::make($input, [
                'name'  => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique(User::class),
                ],
                'password' => $this->passwordRules(),
                'role' => ['required', 'string', Rule::in(['tenant', 'buyer', 'seller', 'landlord', 'contractor'])],
            ])->validate();

            return DB::transaction(function () use ($input) {
                $user = User::create([
                    'name'     => $input['name'],
                    'email'    => $input['email'],
                    'password' => Hash::make($input['password']),
                ]);

                $this->teamManagementService->assignUserToDefaultTeam($user);
                $user->assignRole($input['role']);

                return $user;
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('User creation validation failed', [
                'errors' => $e->errors(),
                'input' => array_diff_key($input, array_flip(['password'])),
            ]);
            throw $e;
        } catch (Exception $e) {
            Log::error('User creation failed', [
                'message' => $e->getMessage(),
                'input' => array_diff_key($input, array_flip(['password'])),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new Exception('Failed to create user. Please try again later.');
        }
    }
}