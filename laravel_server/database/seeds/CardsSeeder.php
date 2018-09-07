<?php

use Illuminate\Database\Seeder;

class CardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      DB::table('decks')->insert([
        'name' => 'default',
        'hidden_face_image_path' => 'deckdefault/semFace.png',
        'active' => '1',
        'complete' => '1'
      ]);

      $this->call(ClubSeeder::class);
      $this->call(DiamondSeeder::class);
      $this->call(HeartSeeder::class);
      $this->call(SpadeSeeder::class);
    }

}
