<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $r)
    {
        return response()->json([
            'profiles' => Profile::where('user_id', $r->user()->id)->get()
        ]);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|max:60',
            'is_kids' => 'boolean',
            'maturity_level' => 'required|string|max:10',
        ]);

        $p = Profile::create([
            'user_id' => $r->user()->id,
            'name' => $data['name'],
            'is_kids' => $data['is_kids'] ?? false,
            'maturity_level' => $data['maturity_level'],
        ]);

        return response()->json(['profile' => $p], 201);
    }

    public function update(Request $r, Profile $profile)
    {
        abort_unless($profile->user_id === $r->user()->id, 403);

        $data = $r->validate([
            'name' => 'sometimes|string|max:60',
            'is_kids' => 'sometimes|boolean',
            'maturity_level' => 'sometimes|string|max:10',
        ]);

        $profile->update($data);
        return response()->json(['profile' => $profile]);
    }

    public function destroy(Request $r, Profile $profile)
    {
        abort_unless($profile->user_id === $r->user()->id, 403);
        $profile->delete();
        return response()->json(['ok' => true]);
    }
}
