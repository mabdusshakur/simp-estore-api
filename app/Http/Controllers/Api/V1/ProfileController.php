<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;

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

            $avatar = $request->file('avatar');
            if($avatar){
                $fileName = time() . '-' . rand(1000, 9999) . '.' . $avatar->getClientOriginalExtension();
                $filePath = $avatar->move(public_path('images\user'), $fileName);
                $user->avatar = $filePath;
            }
            $user->update([
                'name' => $request->name ?? $user->name,
                'email' => $request->email ?? $user->email,
                'phone_number' => $request->phone_number ?? $user->phone_number,
                'address_1' => $request->address_1 ?? $user->address_1,
                'address_2' => $request->address_2 ?? $user->address_2,
                'city' => $request->city ?? $user->city,
                'country' => $request->country ?? $user->country,
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
