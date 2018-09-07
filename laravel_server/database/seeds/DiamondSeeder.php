
<?php

use Illuminate\Database\Seeder;

class DiamondSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {


DB::table('cards')->insert([
        'value' => 'Ace',
        'suite' => 'Diamond',
        'deck_id' => '1',
        'path' => 'deckdefault/o1.png'
      ]);
DB::table('cards')->insert([
        'value' => '2',
        'suite' => 'Diamond',
        'deck_id' => '1',
        'path' => 'deckdefault/o2.png'
      ]);
DB::table('cards')->insert([
        'value' => '3',
        'suite' => 'Diamond',
        'deck_id' => '1',
        'path' => 'deckdefault/o3.png'
      ]);
DB::table('cards')->insert([
        'value' => '4',
        'suite' => 'Diamond',
        'deck_id' => '1',
        'path' => 'deckdefault/o4.png'
      ]);

DB::table('cards')->insert([
        'value' => '5',
        'suite' => 'Diamond',
        'deck_id' => '1',
        'path' => 'deckdefault/o5.png'
      ]);

DB::table('cards')->insert([
        'value' => '6',
        'suite' => 'Diamond',
        'deck_id' => '1',
        'path' => 'deckdefault/o6.png'
      ]);

DB::table('cards')->insert([
        'value' => '7',
        'suite' => 'Diamond',
        'deck_id' => '1',
        'path' => 'deckdefault/o7.png'
      ]);

  DB::table('cards')->insert([
          'value' => 'Jack',
          'suite' => 'Diamond',
          'deck_id' => '1',
          'path' => 'deckdefault/o11.png'
        ]);

  DB::table('cards')->insert([
          'value' => 'Queen',
          'suite' => 'Diamond',
          'deck_id' => '1',
          'path' => 'deckdefault/o12.png'
        ]);

  DB::table('cards')->insert([
          'value' => 'King',
          'suite' => 'Diamond',
          'deck_id' => '1',
          'path' => 'deckdefault/o13.png'
        ]);

            }
        }
