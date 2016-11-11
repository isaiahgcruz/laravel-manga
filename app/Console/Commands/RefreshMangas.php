<?php

namespace App\Console\Commands;

use DB;
use App\Manga;
use Illuminate\Console\Command;


class RefreshMangas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manga:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes manga directory';

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
        $this->line('Loading mangas...');
        DB::table('mangas')->truncate();
        $json = file_get_contents('http://www.mangaeden.com/api/list/0/');
        $data = json_decode($json, true);
        $mangas = $data['manga'];
        foreach ($mangas as $manga) {
            Manga::create([
                'id' => $manga['i'],
                'title' => $manga['t'],
            ]);
        }
        $this->line('Finished loading the mangas.');
    }
}
