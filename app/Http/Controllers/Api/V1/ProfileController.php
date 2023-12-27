<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show()
    {
        return response()->json([
            'status' => 'success',
            'data' => auth()->user()
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            $request->validated();
            $user = auth()->user();

            if ($request->hasFile('avatar')) {
                $request->validate(['avatar' => 'required|image|max:2048']);
                $avatar = $request->file('avatar');
                $avatarName = time() . '-' . rand(1000, 9999) . '.' . $avatar->getClientOriginalExtension();
                $avatarPath = $avatar->move(public_path('images/user'), $avatarName);
                $user->avatar = $avatarPath;
            }

            $user->update([
                'name' => $request->name ?? $user->name,
                'email' => $request->email ?? $user->email,
                'phone_number' => $request->phone_number ?? $user->phone_number,
                'address_1' => $request->address_1 ?? $user->address_1,
                'address_2' => $request->address_2 ?? $user->address_2,
                'city' => $request->city ?? $user->city,
                'postal_code' => $request->postal_code ?? $user->postal_code,
            ]);

            $user->save();
            return response()->json([
                'status' => 'success',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
}
