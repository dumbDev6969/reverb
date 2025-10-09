<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Report;
use Illuminate\Auth\Access\Response;
class ReportPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
     
    public function update(User $user, Report $report) 
    {
        return $user->id === $report->user_id ? 
        Response::allow() : false;
    }
}
 