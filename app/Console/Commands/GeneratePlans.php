<?php

namespace taskbee\Console\Commands;

use Exception;
use taskbee\Models\Plan;
use Illuminate\Console\Command;
use taskbee\Billing\StripePlansGateway;

class GeneratePlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:plans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate subscription plans. Run only once, at the beginning of the journey.';

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     * @return void
     */
    public function handle(): void
    {
        try {
            $this->runCheckBefore();

            (new StripePlansGateway(config('services.stripe.secret')))->generate();

            $this->runCheckAfter();
        } catch (Exception $e) {
            $this->warn($e->getMessage());
        }
    }

    /**
     * Check if the plans have already been generated.
     *
     * @throws \Throwable
     */
    public function runCheckBefore(): void
    {
        throw_if(
            Plan::count() > 0,
            new Exception(
                'Looks like you already have plans created.
                 Please check your database, there should be no plans there prior to running this command.'
            )
        );

        $this->info('Preparing.. please wait.');
    }

    /**
     * Check the number of plans in the DB after their creation.
     *
     * @throws \Exception
     */
    public function runCheckAfter(): void
    {
        if (Plan::count() !== 3) {
            throw new Exception('Something went wrong.');
        }

        $this->info('Done!');
    }
}
