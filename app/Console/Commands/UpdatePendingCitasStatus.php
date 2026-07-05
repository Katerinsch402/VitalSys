<?php

namespace App\Console\Commands;

use App\Models\Cita;
use Illuminate\Console\Command;

class UpdatePendingCitasStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citas:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar el estado de las citas pendientes a concluido cuando ya haya pasado el tiempo de fin de la cita.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $updated = Cita::markOverdueAsConcluded();

        $this->info("Citas actualizadas: {$updated}");

        return 0;
    }
}
