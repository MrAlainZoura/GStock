<?php

namespace App\Jobs;

use App\Models\UserRole;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Http\Controllers\RapportController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendDailyReport implements ShouldQueue
{
     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    { 
        Log::info('Job started envoi de mail ');

        try{
            $userRoles = UserRole::whereHas('role', function ($query) {
                $query->whereIn('libele', ['Super admin', 'Administrateur']);
                })
            ->with(['user.depot'])
            ->get();
            $rapport = new RapportController();
            foreach( $userRoles as $userRole ) {
                // $to = $userRole->user->email;
                $to = "a.tshiyanze@gmail.com";
                foreach($userRole->user->depot as $depot ) {
                    // dd($depot->id);
                    $sendMailRapport=$rapport->rapport_send_mail($to,$depot->libele,$depot->id);
                }
    
            }
            
        }catch(\Exception $e){
            Log::error('Job failed: ' . $e->getMessage());

        }
    }
}
