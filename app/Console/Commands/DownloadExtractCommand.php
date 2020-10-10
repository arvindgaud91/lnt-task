<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DownloadExtractCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:ZipFileAndExtract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download zip file and Extract';

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
        $zip_file = 'test.zip'; // Name of our archive to download

        // Initializing PHP class
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $file_path = storage_path('upload');
        $options = array('add_path' => $file_path, 'remove_all_path' => TRUE);
        $zip->addGlob('*.{txt}', GLOB_BRACE, $options);
        $zip->close();
        // We return the file immediately after download
        //return response()->download($zip_file)->deleteFileAfterSend(true);
    }
}
