<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nid_number' => 'required|string|max:20',
            'division_id' => 'required|exists:divisions,id',
            'district_id' => 'required|exists:districts,id',
            'thana_id' => 'required|exists:thanas,id',
            'address' => 'required|string|max:500',
            'company_name' => 'required|string|max:255',
            'profile_pic' => 'nullable|image|max:2048',
            'nid_front' => 'nullable|image|max:2048',
            'nid_back' => 'nullable|image|max:2048',
        ]);

        $user->fill($request->only([
            'name', 'nid_number', 'division_id', 'district_id', 'thana_id', 'address', 'company_name'
        ]));

        if ($request->hasFile('profile_pic')) {
            if ($user->profile_pic) {
                Storage::delete($user->profile_pic);
            }
            $user->profile_pic = $request->file('profile_pic')->store('profile-photos', 'public');
        }

        if ($request->hasFile('nid_front')) {
            if ($user->nid_front) {
                Storage::delete($user->nid_front);
            }
            $user->nid_front = $request->file('nid_front')->store('nid-photos', 'public');
        }

        if ($request->hasFile('nid_back')) {
            if ($user->nid_back) {
                Storage::delete($user->nid_back);
            }
            $user->nid_back = $request->file('nid_back')->store('nid-photos', 'public');
        }

        $user->save();
        
        // Eager load relationships for the response
        $user->load('division', 'district', 'thana');

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
            'profile_pic_url' => $user->profile_pic ? asset('storage/' . $user->profile_pic) : null
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $user->load('division', 'district', 'thana');
        $user->profile_pic_url = $user->profile_pic ? asset('storage/' . $user->profile_pic) : null;
        
        return response()->json($user);
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:4',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'errors' => ['current_password' => ['The provided password does not match your current password.']]
            ], 422);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return response()->json(['message' => 'Password updated successfully.']);
    }
    public function updateMobile(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'mobile' => 'required|string|max:15|unique:users,mobile,' . $request->user()->id,
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'errors' => ['current_password' => ['The provided password does not match your current password.']]
            ], 422);
        }

        $user->forceFill([
            'mobile' => $request->mobile,
        ])->save();

        return response()->json(['message' => 'Mobile number updated successfully.']);
    }
}
