<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Cronjob\CronjobController;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('inspire')
				 ->hourly();

		$schedule->call(function() {

            $customerController = new CustomerController();
			$customerController->countUserDomainsFunction();

        })->cron('0 0 * * *');//daily();


		$schedule->call(function() {

            $customerController = new CronjobController();
			$customerController->test2();

        })->cron('0 0 * * *');//daily();
		

	}

}
