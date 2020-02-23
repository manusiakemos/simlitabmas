<?php

namespace App\Console\Commands;

use App\Models\FileModel;
use App\Models\Penelitian;
use App\Models\PenelitianAnggota;
use App\Models\Pengabdian;
use App\Models\SurveyFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ResetApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset Aplikasi';

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
        $files =   Storage::allFiles('/uploads/penelitian');
        Storage::delete($files);

        Penelitian::truncate();
        Pengabdian::truncate();
        FileModel::truncate();
        PenelitianAnggota::truncate();
    }
}
