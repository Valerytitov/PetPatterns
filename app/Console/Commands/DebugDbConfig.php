<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class DebugDbConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:db-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dumps the current database configuration that Laravel is using.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dumping current MySQL connection config...');
        
        // Получаем массив с конфигурацией из кэша или .env
        $config = Config::get('database.connections.mysql');

        // Выводим его в удобном виде
        print_r($config);
        
        return 0;
    }
}