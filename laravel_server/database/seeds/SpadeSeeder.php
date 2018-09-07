
<?php

use Illuminate\Database\Seeder;

class SpadeSeeder extends Seeder
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
        'suite' => 'Spade',
        'deck_id' => '1',
        'path' => 'deckdefault/e1.png'
      ]);
DB::table('cards')->insert([
        'value' => '2',
        'suite' => 'Spade',
        'deck_id' => '1',
        'path' => 'deckdefault/e2.png'
      ]);
DB::table('cards')->insert([
        'value' => '3',
        'suite' => 'Spade',
        'deck_id' => '1',
        'path' => 'deckdefault/e3.png'
      ]);
DB::table('cards')->insert([
        'value' => '4',
        'suite' => 'Spade',
        'deck_id' => '1',
        'path' => 'deckdefault/e4.png'
      ]);

DB::table('cards')->insert([
        'value' => '5',
        'suite' => 'Spade',
        'deck_id' => '1',
        'path' => 'deckdefault/e5.png'
      ]);

DB::table('cards')->insert([
        'value' => '6',
        'suite' => 'Spade',
        'deck_id' => '1',
        'path' => 'deckdefault/e6.png'
      ]);

DB::table('cards')->insert([
        'value' => '7',
        'suite' => 'Spade',
        'deck_id' => '1',
        'path' => 'deckdefault/e7.png'
      ]);

  DB::table('cards')->insert([
          'value' => 'Jack',
          'suite' => 'Spade',
          'deck_id' => '1',
          'path' => 'deckdefault/e11.png'
        ]);

  DB::table('cards')->insert([
          'value' => 'Queen',
          'suite' => 'Spade',
          'deck_id' => '1',
          'path' => 'deckdefault/e12.png'
        ]);

  DB::table('cards')->insert([
          'value' => 'King',
          'suite' => 'Spade',
          'deck_id' => '1',
          'path' => 'deckdefault/e13.png'
        ]);

            }
        }
