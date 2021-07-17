<?php

namespace App\Console\Commands\Indexer;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;


class MangaIndexer extends Command
{
    /**
     * The name and signature of the console command.
     *`
     * @var string
     */
    protected $signature = 'indexer:manga';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index all manga';

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

        echo "Note: MangaIndexer uses seanbreckenridge/mal-id-cache fetch available MAL IDs and updates/indexes them\n\n";

        /**
         *
         */
        // https://github.com/seanbreckenridge/mal-id-cache
        echo "Fetching MAL ID Cache https://raw.githubusercontent.com/seanbreckenridge/mal-id-cache/master/cache/manga_cache.json...\n";

        $ids = json_decode(
            file_get_contents('https://raw.githubusercontent.com/seanbreckenridge/mal-id-cache/master/cache/manga_cache.json'),
            true
        );
        $ids = $ids['sfw'] + $ids['nsfw']; // merge
        Storage::put('manga_mal_id.json', json_encode($ids));

        echo "Loading MAL IDs\n";
        $ids = json_decode(Storage::get('manga_mal_id.json'));
        $count = count($ids);

        echo "{$count} entries available\n";
        foreach ($ids as $i => $id) {
            $url = env('APP_URL') . "/v4/manga/{$id}";

            echo "Indexing/Updating ".($i+1)."/{$count} {$url} [MAL ID: {$id}] \n";

            try {
                $response = json_decode(file_get_contents($url), true);

                if (isset($response['error'])) {
                    echo "[SKIPPED] Failed to fetch {$url} - {$response['error']}\n";
                }

                sleep(1);
            } catch (\Exception $e) {
                echo "[SKIPPED] Failed to fetch {$url}\n";
            }
        }

        echo str_pad("Indexing complete", 100).PHP_EOL;
    }
}