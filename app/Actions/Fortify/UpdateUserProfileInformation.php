<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Exception;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function update(User $user, array $input): void
    {
        try {
            Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($user->id),
                ],
            ])->validateWithBag('updateProfileInformation');

            if ($input['email'] !== $user->email &&
                $user instanceof MustVerifyEmail) {
                $this->updateVerifiedUser($user, $input);
            } else {
                $user->forceFill([
                    'name' => $input['name'],
                    'email' => $input['email'],
                ])->save();
            }

            Log::info('User profile updated successfully', ['user_id' => $user->id]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Profile update validation failed', [
                'user_id' => $user->id,
                'errors' => $e->errors(),
            ]);
            throw $e;
        } catch (Exception $e) {
            Log::error('Profile update failed', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new Exception('Failed to update profile. Please try again later.');
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     * @throws \Exception
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        try {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'email_verified_at' => null,
            ])->save();

            $user->sendEmailVerificationNotification();

            Log::info('Verified user profile updated successfully', ['user_id' => $user->id]);
        } catch (Exception $e) {
            Log::error('Failed to update verified user profile', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new Exception('Failed to update profile. Please try again later.');
        }
    }
}
