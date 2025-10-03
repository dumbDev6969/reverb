<?php
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;
// This route checks if a user is allowed to listen to the 'moderator-reports' channel.
Broadcast::channel('moderator-reports', function ($user) {
    // Return true if they are authorized, false if not.
    
    return Auth::user()->id === 1;
});