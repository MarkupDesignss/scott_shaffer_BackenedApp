<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\TournamentRegistration;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TournamentRegistrationController extends Controller
{
    /**
     * Solo Registration
     * POST /api/tournaments/{id}/register/solo
     */
    public function soloRegister(Request $request, $id)
    {
        $user = $request->user();
        $tournament = Tournament::findOrFail($id);

        if (!$tournament->is_registration_open) {
            return response()->json(['message' => 'Registration is closed'], 422);
        }

        if ($tournament->max_participants && $tournament->registered_participants >= $tournament->max_participants) {
            return response()->json(['message' => 'Tournament is full'], 422);
        }

        $exists = TournamentRegistration::where('tournament_id', $tournament->id)
            ->where('user_id', $user->id)
            ->where('type', 'solo')
            ->first();

        if ($exists) {
            return response()->json(['message' => 'Already registered'], 422);
        }

        $registration = TournamentRegistration::create([
            'tournament_id' => $tournament->id,
            'type' => 'solo',
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'phone' => $user->mobile ?? $user->phone,
            'user_id' => $user->id,
        ]);

        $tournament->increment('registered_participants');

        return response()->json([
            'message' => 'Successfully registered for solo',
            'registration' => $registration
        ]);
    }

    /**
     * Team Registration (Create Team)
     * POST /api/tournaments/{id}/register/team
     */
    public function teamRegister(Request $request, $id)
    {
        $user = $request->user();

        $request->validate([
            'team_name' => 'required|string|max:191',
            'team_tag' => 'required|string|max:50',
            'team_logo' => 'nullable|image|max:2048',
        ]);

        $tournament = Tournament::findOrFail($id);

        if (!$tournament->is_registration_open) {
            return response()->json(['message' => 'Registration is closed'], 422);
        }

        // Duplicate team check
        $duplicate = TournamentRegistration::where('tournament_id', $tournament->id)
            ->where('type', 'team')
            ->where(function ($q) use ($request) {
                $q->where('team_name', $request->team_name)
                  ->orWhere('team_tag', $request->team_tag);
            })
            ->exists();

        if ($duplicate) {
            return response()->json(['message' => 'Team name or tag already exists'], 422);
        }

        // Upload team logo
        $teamLogoPath = null;
        if ($request->hasFile('team_logo')) {
            $teamLogoPath = $request->file('team_logo')->store('teams', 'public');
        }

        // Generate unique invite code
        do {
            $inviteCode = Str::random(16);
        } while (TournamentRegistration::where('invite_link', $inviteCode)->exists());

        $registration = TournamentRegistration::create([
            'tournament_id' => $tournament->id,
            'type' => 'team',
            'team_name' => $request->team_name,
            'team_tag' => $request->team_tag,
            'team_logo' => $teamLogoPath,
            'is_captain' => true,
            'invite_link' => $inviteCode,
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'phone' => $user->mobile ?? $user->phone,
            'user_id' => $user->id,
        ]);

        $tournament->increment('registered_participants');

        // Frontend invite URL
        //$tournamentSlug = Str::slug($tournament->title);
        $tournamentTitle = rawurlencode($tournament->title);
        $inviteUrl = 'http://localhost:5173/tourmainpage/'
            . $tournamentTitle
            . '?invite=' . $inviteCode;

        return response()->json([
            'message' => 'Team created successfully',
            'registration' => $registration,
            'invite_link' => $inviteUrl,
            'team_logo_url' => $teamLogoPath ? asset('storage/' . $teamLogoPath) : null,
        ]);
    }

    /**
     * Generate Invite Link (Existing Team)
     */
    public function generateInviteLink(Request $request, $id)
    {
        $user = $request->user();

        $registration = TournamentRegistration::where('tournament_id', $id)
            ->where('user_id', $user->id)
            ->where('type', 'team')
            ->firstOrFail();

        if (!$registration->invite_link) {
            do {
                $registration->invite_link = Str::random(16);
            } while (TournamentRegistration::where('invite_link', $registration->invite_link)->exists());

            $registration->save();
        }

        $tournament = Tournament::findOrFail($registration->tournament_id);
        //$tournamentSlug = Str::slug($tournament->title);
        $tournamentTitle = rawurlencode($tournament->title);

        $inviteUrl = 'http://localhost:5173/tourmainpage/'
            . $tournamentTitle
            . '?invite=' . $registration->invite_link;

        return response()->json([
            'invite_link' => $inviteUrl
        ]);
    }

    /**
     * Join Team via Invite Code
     * POST /api/tournaments/join-team
     */
    public function joinTeam(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'invite_code' => 'required|string',
        ]);

        $team = TournamentRegistration::where('invite_link', $request->invite_code)
            ->where('type', 'team')
            ->firstOrFail();
        dd($team);
        $tournament = Tournament::findOrFail($team->tournament_id);

        if (!$tournament->is_registration_open) {
            return response()->json(['message' => 'Registration is closed'], 422);
        }

        // Already joined?
        $exists = TournamentRegistration::where('invite_link', $team->invite_link)
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Already part of this team'], 422);
        }

        // Team size check
        $membersCount = TournamentRegistration::where('invite_link', $team->invite_link)->count();
        if ($tournament->team_size && $membersCount >= $tournament->team_size) {
            return response()->json(['message' => 'Team is full'], 422);
        }

        $registration = TournamentRegistration::create([
            'tournament_id' => $tournament->id,
            'type' => 'team',
            'team_name' => $team->team_name,
            'team_tag' => $team->team_tag,
            'team_logo' => $team->team_logo,
            'is_captain' => false,
            'invite_link' => $team->invite_link,
            'user_id' => $user->id,
        ]);

        $tournament->increment('registered_participants');

        return response()->json([
            'message' => 'Joined team successfully',
            'registration' => $registration,
            'team_logo_url' => $team->team_logo ? asset('storage/' . $team->team_logo) : null,
        ]);
    }
}