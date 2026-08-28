<?php

namespace App\Console\Commands;

use App\Models\Offer;
use Illuminate\Console\Command;

class ExpireOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-offers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $offers = Offer::whereDate('fin', '<', today())->get();

        $this->info("Ofertas vencidas encontradas : {$offers->count()}");

        foreach($offers as $offer){

            $count = $offer->products()->update([
                'offer_id' => null,
            ]);

            $this->info(
                "Oferta {$offer->id}: {$count} productos desvinculados"
            );
        }

        return self::SUCCESS;
    }
}
