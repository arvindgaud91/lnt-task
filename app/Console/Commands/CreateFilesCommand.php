<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class CreateFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:files {length=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create n number of text files';

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
        for($i=1; $i <= $length; $i++){
            $file_name = 'upload/file'.$i.'.txt';
            Storage::disk('local')->put($file_name, 'Contents');
        }
        
        $this->info('files created successfully');
    }
}
