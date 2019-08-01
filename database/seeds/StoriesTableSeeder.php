<?php

use Illuminate\Database\Seeder;
use App\Models\Story;

class StoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/stories.json");
        $data = json_decode($json);
        foreach($data as $story){
            $stories[] = [
                'id' => $story->id,
                'name' => $story->name,
                'take_days' => $story->take_days,
                'daily_tasks_lisk' => json_encode($story->daily_tasks_lisk),
            ];    
        }

        Story::insert($stories);
    }
}
