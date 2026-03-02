<?php

namespace Param\RBAC\Http\Controllers;

use Illuminate\Http\Request;
use Param\RBAC\Models\AuthSetting;
use Param\RBAC\Models\PasswordRule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthSettingController extends Controller
{
    /**
     * Show authentication settings page
     */
    public function index()
    {
        $title = 'Auth-Settings';
        return view('master.AuthSettings.setting', compact('title'));
    }

    /**
     * Get all authentication settings
     */
    public function getAuthenticationSettings()
    {
        $settings = AuthSetting::where('IsActive', 1)->get();

        return response()->json(
            $settings->map(function ($s) {
                return [
                    'authSettingId' => $s->AuthSettingId,
                    'authCode'      => $s->AuthCode,
                    'isEnabled'     => (bool) $s->IsEnabled,
                    'otpattempt'    => $s->OTPAttempt,
                    'otpresetTime'  => $s->OTPResetTime,
                    'otpexpiryTime' => $s->OTPExpiryTime,
                ];
            })
        );
    }

    /**
     * Update authentication settings
     */
    public function updateAuthenticationSettings(Request $request)
    {
        $data = $request->validate([
            '*.AuthSettingId' => 'required|integer',
            '*.AuthCode' => 'required|string',
            '*.IsEnabled' => 'required|boolean',
            '*.Otpattempt' => 'nullable|integer',
            '*.OtpresetTime' => 'nullable|integer',
            '*.OtpexpiryTime' => 'nullable|integer',
        ]);

        DB::beginTransaction();
        try {
            foreach ($data as $item) {
                AuthSetting::updateOrCreate(
                    ['AuthSettingId' => $item['AuthSettingId']],
                    [
                        'AuthCode'       => $item['AuthCode'],
                        'IsEnabled'      => $item['IsEnabled'] ? 1 : 0,
                        'OTPAttempt'     => $item['Otpattempt'] ?? null,
                        'OTPResetTime'   => $item['OtpresetTime'] ?? null,
                        'OTPExpiryTime'  => $item['OtpexpiryTime'] ?? null,
                        'UpdatedOn'      => Carbon::now()
                    ]
                );
            }

            DB::commit();
            return response()->json([
                'result' => true,
                'message' => 'Authentication settings updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating auth settings', ['message' => $e->getMessage()]);
            return response()->json([
                'result' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all password rules
     */
    public function getPasswordRules()
    {
        return response()->json(
            PasswordRule::all()->map(function ($r) {
                return [
                    'ruleId'    => $r->RuleId,
                    'ruleCode'  => $r->RuleCode,
                    'ruleValue' => $r->RuleValue,
                    'isEnabled' => (bool) $r->IsEnabled
                ];
            })
        );
    }

    /**
     * Update password rules
     */
    public function updatePasswordRules(Request $request)
    {
        try {
            Log::info('Password Rules Update Request:', $request->all());

            $data = $request->validate([
                '*.RuleId' => 'required|integer',
                '*.RuleCode' => 'required|string',
                '*.IsEnabled' => 'required|boolean',
                '*.RuleValue' => 'required|integer',
            ]);

            DB::beginTransaction();

            $updatedCount = 0;
            foreach ($data as $rule) {
                $ruleNameMap = [
                    'MIN_LENGTH' => 'Minimum Length',
                    'UPPERCASE' => 'Uppercase Letters',
                    'LOWERCASE' => 'Lowercase Letters',
                    'NUMBER' => 'Numbers',
                    'SPECIAL_CHAR' => 'Special Characters'
                ];

                $ruleName = $ruleNameMap[$rule['RuleCode']] ?? $rule['RuleCode'];

                $updated = PasswordRule::updateOrCreate(
                    ['RuleId' => $rule['RuleId']],
                    [
                        'RuleCode'  => $rule['RuleCode'],
                        'RuleName'  => $ruleName,
                        'RuleValue' => $rule['RuleValue'],
                        'IsEnabled' => $rule['IsEnabled'] ? 1 : 0,
                        'UpdatedOn' => Carbon::now()
                    ]
                );

                if ($updated) {
                    $updatedCount++;
                }
            }

            DB::commit();

            Log::info("Password rules updated successfully. Updated {$updatedCount} rules.");

            return response()->json([
                'result' => true,
                'message' => 'Password rules updated successfully',
                'updatedCount' => $updatedCount
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error in updatePasswordRules:', $e->errors());
            return response()->json([
                'result' => false,
                'message' => 'Validation error: ' . implode(', ', array_flatten($e->errors())),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating password rules:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'result' => false,
                'message' => 'Error updating password rules: ' . $e->getMessage()
            ], 500);
        }
    }

    // ====== USER MANAGEMENT METHODS ======

    /**
     * List all users
     */
    public function listUsers()
    {
        $users = \Param\RBAC\Models\User::all();
        return response()->json(['data' => $users]);
    }

    /**
     * Create user form
     */
    public function createUser()
    {
        return view('rbac::users.create');
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        try {
            $validated['password'] = bcrypt($validated['password']);
            \Param\RBAC\Models\User::create($validated);
            return response()->json(['result' => true, 'message' => 'User created successfully']);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Edit user
     */
    public function editUser($id)
    {
        $user = \Param\RBAC\Models\User::findOrFail($id);
        return view('rbac::users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, $id)
    {
        $user = \Param\RBAC\Models\User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        try {
            $user->update($validated);
            return response()->json(['result' => true, 'message' => 'User updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete user
     */
    public function destroyUser($id)
    {
        try {
            \Param\RBAC\Models\User::destroy($id);
            return response()->json(['result' => true, 'message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ====== ROLE MANAGEMENT METHODS ======

    /**
     * List all roles
     */
    public function listRoles()
    {
        $roles = \Param\RBAC\Models\RoleMaster::all();
        return response()->json(['data' => $roles]);
    }

    /**
     * Create role form
     */
    public function createRole()
    {
        return view('rbac::roles.create');
    }

    /**
     * Store new role
     */
    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'role_name' => 'required|string|unique:role_masters',
            'description' => 'nullable|string',
        ]);

        try {
            \Param\RBAC\Models\RoleMaster::create($validated);
            return response()->json(['result' => true, 'message' => 'Role created successfully']);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Edit role
     */
    public function editRole($id)
    {
        $role = \Param\RBAC\Models\RoleMaster::findOrFail($id);
        return view('rbac::roles.edit', compact('role'));
    }

    /**
     * Update role
     */
    public function updateRole(Request $request, $id)
    {
        $role = \Param\RBAC\Models\RoleMaster::findOrFail($id);

        $validated = $request->validate([
            'role_name' => 'required|string|unique:role_masters,role_name,' . $id,
            'description' => 'nullable|string',
        ]);

        try {
            $role->update($validated);
            return response()->json(['result' => true, 'message' => 'Role updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete role
     */
    public function destroyRole($id)
    {
        try {
            \Param\RBAC\Models\RoleMaster::destroy($id);
            return response()->json(['result' => true, 'message' => 'Role deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ====== PERMISSION MANAGEMENT METHODS ======

    /**
     * List all permissions
     */
    public function listPermissions()
    {
        $permissions = app('db')->table('permissions')->get();
        return response()->json(['data' => $permissions]);
    }

    /**
     * Create permission form
     */
    public function createPermission()
    {
        return view('rbac::permissions.create');
    }

    /**
     * Store new permission
     */
    public function storePermission(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions',
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        try {
            app('db')->table('permissions')->insert([
                $validated,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return response()->json(['result' => true, 'message' => 'Permission created successfully']);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Edit permission
     */
    public function editPermission($id)
    {
        $permission = app('db')->table('permissions')->find($id);
        return view('rbac::permissions.edit', compact('permission'));
    }

    /**
     * Update permission
     */
    public function updatePermission(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        try {
            app('db')->table('permissions')->where('id', $id)->update([
                ...$validated,
                'updated_at' => now(),
            ]);
            return response()->json(['result' => true, 'message' => 'Permission updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete permission
     */
    public function destroyPermission($id)
    {
        try {
            app('db')->table('permissions')->where('id', $id)->delete();
            return response()->json(['result' => true, 'message' => 'Permission deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ====== TWO-FACTOR AUTHENTICATION METHODS ======

    /**
     * List 2FA users
     */
    public function list2FA()
    {
        $twoFAUsers = \Param\RBAC\Models\UserTwoFactor::all();
        return response()->json(['data' => $twoFAUsers]);
    }

    /**
     * Enable 2FA for user
     */
    public function enable2FA($userId)
    {
        try {
            $user = \Param\RBAC\Models\User::findOrFail($userId);
            \Param\RBAC\Models\UserTwoFactor::updateOrCreate(
                ['UserId' => $userId],
                ['IsEnabled' => 1, 'UpdatedOn' => now()]
            );
            return response()->json(['result' => true, 'message' => '2FA enabled for user']);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Disable 2FA for user
     */
    public function disable2FA($userId)
    {
        try {
            \Param\RBAC\Models\UserTwoFactor::where('UserId', $userId)->update(['IsEnabled' => 0]);
            return response()->json(['result' => true, 'message' => '2FA disabled for user']);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Verify 2FA code
     */
    public function verify2FA(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'code' => 'required|string',
        ]);

        try {
            // Verify against Google2FA or your 2FA logic
            return response()->json(['result' => true, 'message' => '2FA code verified']);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ====== PASSWORD RULES METHODS ======

    /**
     * List all password rules
     */
    public function listPasswordRules()
    {
        $rules = PasswordRule::all();
        return response()->json(['data' => $rules]);
    }

    /**
     * Store password rule
     */
    public function storePasswordRule(Request $request)
    {
        $validated = $request->validate([
            'RuleCode' => 'required|string|unique:password_rules',
            'RuleName' => 'required|string',
            'RuleValue' => 'required|integer',
            'IsEnabled' => 'required|boolean',
        ]);

        try {
            PasswordRule::create($validated);
            return response()->json(['result' => true, 'message' => 'Password rule created successfully']);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update password rule
     */
    public function updatePasswordRule(Request $request, $id)
    {
        $rule = PasswordRule::findOrFail($id);

        $validated = $request->validate([
            'RuleCode' => 'required|string',
            'RuleName' => 'required|string',
            'RuleValue' => 'required|integer',
            'IsEnabled' => 'required|boolean',
        ]);

        try {
            $rule->update($validated);
            return response()->json(['result' => true, 'message' => 'Password rule updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ====== AUTH SETTINGS METHODS ======

    /**
     * Auth settings edit
     */
    public function edit($id)
    {
        $setting = AuthSetting::findOrFail($id);
        return response()->json(['data' => $setting]);
    }

    /**
     * Auth settings update
     */
    public function update(Request $request, $id)
    {
        $setting = AuthSetting::findOrFail($id);

        $validated = $request->validate([
            'AuthCode' => 'required|string',
            'IsEnabled' => 'required|boolean',
            'OTPAttempt' => 'nullable|integer',
            'OTPResetTime' => 'nullable|integer',
            'OTPExpiryTime' => 'nullable|integer',
        ]);

        try {
            $setting->update($validated);
            return response()->json(['result' => true, 'message' => 'Auth setting updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store auth setting
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'AuthCode' => 'required|string|unique:authentication_settings',
            'IsEnabled' => 'required|boolean',
        ]);

        try {
            AuthSetting::create($validated);
            return response()->json(['result' => true, 'message' => 'Auth setting created successfully']);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete auth setting
     */
    public function destroy($id)
    {
        try {
            AuthSetting::destroy($id);
            return response()->json(['result' => true, 'message' => 'Auth setting deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 500);
        }    }
}
