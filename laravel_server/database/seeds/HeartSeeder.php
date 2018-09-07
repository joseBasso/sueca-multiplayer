
<?php

use Illuminate\Database\Seeder;

class HeartSeeder extends Seeder
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
        'suite' => 'Heart',
        'deck_id' => '1',
        'path' => 'deckdefault/c1.png'
      ]);
DB::table('cards')->insert([
        'value' => '2',
        'suite' => 'Heart',
        'deck_id' => '1',
        'path' => 'deckdefault/c2.png'
      ]);
DB::table('cards')->insert([
        'value' => '3',
        'suite' => 'Heart',
        'deck_id' => '1',
        'path' => 'deckdefault/c3.png'
      ]);
DB::table('cards')->insert([
        'value' => '4',
        'suite' => 'Heart',
        'deck_id' => '1',
        'path' => 'deckdefault/c4.png'
      ]);

DB::table('cards')->insert([
        'value' => '5',
        'suite' => 'Heart',
        'deck_id' => '1',
        'path' => 'deckdefault/c5.png'
      ]);

DB::table('cards')->insert([
        'value' => '6',
        'suite' => 'Heart',
        'deck_id' => '1',
        'path' => 'deckdefault/c6.png'
      ]);

DB::table('cards')->insert([
        'value' => '7',
        'suite' => 'Heart',
        'deck_id' => '1',
        'path' => 'deckdefault/c7.png'
      ]);

  DB::table('cards')->insert([
          'value' => 'Jack',
          'suite' => 'Heart',
          'deck_id' => '1',
          'path' => 'deckdefault/c11.png'
        ]);

  DB::table('cards')->insert([
          'value' => 'Queen',
          'suite' => 'Heart',
          'deck_id' => '1',
          'path' => 'deckdefault/c12.png'
        ]);

  DB::table('cards')->insert([
          'value' => 'King',
          'suite' => 'Heart',
          'deck_id' => '1',
          'path' => 'deckdefault/c13.png'
        ]);

            }
        }
