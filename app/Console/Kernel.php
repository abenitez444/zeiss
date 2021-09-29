<?php

namespace App\Console;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\GetFacturasCommand::class,
        // Commands\GetOrdenesCommand::class,
        // Commands\GetPagosCommand::class,
        // Commands\GetComponentesCommand::class,
        // Commands\GetPagosProveedorCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('get-facturas')
        //     ->everyThirtyMinutes()
        //     ->before(function () {
        //         Log::info('Se comienza ejecucion de comando de facturas '.date('Y-m-d H:i:s'), []);
        //     })
        //     ->after(function () {
        //         Log::info('Se termina ejecucion de comando de facturas '.date('Y-m-d H:i:s'), []);
        //     });

        // $schedule->command('get-ordenes')
        //     ->hourlyAt(15)
        //     ->before(function () {
        //         Log::info('Se comienza ejecucion de comando de ordenes '.date('Y-m-d H:i:s'), []);
        //     })
        //     ->after(function () {
        //         Log::info('Se termina ejecucion de comando de ordenes '.date('Y-m-d H:i:s'), []);
        //     });

        // $schedule->command('get-componentes')
        //     ->hourlyAt(45)
        //     ->before(function () {
        //         Log::info('Se comienza ejecucion de comando de complementos '.date('Y-m-d H:i:s'), []);
        //     })
        //     ->after(function () {
        //         Log::info('Se termina ejecucion de comando de complementos '.date('Y-m-d H:i:s'), []);
        //     });

        // $schedule->command('get-pagos')
        //    ->everyMinute()
            //->hourlyAt(25)
        //    ->before(function () {
        //        Log::info('Se comienza ejecucion de comando de pagos '.date('Y-m-d H:i:s'), []);
        //    })
        //    ->after(function () {
        //        Log::info('Se termina ejecucion de comando de pagos '.date('Y-m-d H:i:s'), []);
        //    });

        $schedule->command('get-pagos-proveedor')
            ->hourlyAt(35)
            ->before(function () {
                Log::info('Se comienza ejecucion de comando de pagos proveedor'.date('Y-m-d H:i:s'), []);
            })
            ->after(function () {
                Log::info('Se termina ejecucion de comando de pagos proveedor'.date('Y-m-d H:i:s'), []);
            });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
