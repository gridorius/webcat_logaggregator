<?php

namespace App\Console;

use App\Log;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function(){
          $config = Storage::disk('base')->get('.logconf');
          $config_params = [];

          preg_replace_callback('/(.+)=(.+)/', function($matches) use (&$config_params){
            $config_params[$matches[1]] = $matches[2];
          }, $config);

          $logs = Storage::disk('base')->get($config_params['LOG_PATH']);
          $array_log = preg_split('/\n/', $logs);

          $names = [];

          $regex = preg_replace_callback('/{(.+?)}/', function($matches) use (&$names){
            if($matches[1] == '?')
              return '.+?';
            else{
              $names[] = $matches[1];
              return '(.+?)';
            }

          }, $config_params['LOG_MASK']);

          $finaly = [];

          preg_replace_callback("/$regex/", function($matches) use ($names, &$finaly){
            $log = [];
            foreach ($names as $i => $name)
              $log[$name] = $matches[$i+1];

            $finaly[] = $log;
          }, $array_log);

          foreach($finaly as $log){
            $Log = new Log($log);
            $Log->date = date('Y.m.d h:i', strtotime($log['date']));
            $Log->save();
          }

          Storage::disk('base')->delete($config_params['LOG_PATH']);
        })->everyTenMinutes();;
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
