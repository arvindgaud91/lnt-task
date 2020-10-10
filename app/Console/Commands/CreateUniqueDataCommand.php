<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateUniqueDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:data {length=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create n number of unique records';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $length = (int)$this->argument('length');
        factory(\App\model\Task::class, $length)->create();
        $this->info('Records generated Successfully.');
    }
}
