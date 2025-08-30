<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Depot;
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
    protected $depot; 
    public function __construct($depot)
    {
        $this->depot = $depot;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    { 
        Log::info('Job started envoi de mail ');
        
        try{
            $depot_id = $this->depot;
            $getDepot = Depot::find($depot_id);
            $pdf = RapportController::genererPDF($depot_id, 'today');
            $today = Carbon::now()->format('Y-m-d');
            $to = $getDepot->user->email;
            $sendMailRapport = RapportController::rapport_send_mail($to, $getDepot->libele, $getDepot->id);

            if ($sendMailRapport->getData()->status === true) {
                // Optionally store the PDF or notify user
                Log::info('Job executer avec success envoi de mail ');
            } else {
                Log::info('Job executer avec success envoi de mail MAIS ECHEC D"ENVOI D EMAIL'.$sendMailRapport->getData()->details);
                // Log or handle email failure
            }


            Log::info('Job executer avec success envoi de mail ');
        }catch(\Exception $e){
            Log::error('Job failed: ' . $e->getMessage());
        }
    }
}
