
<?php

use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
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
                    'suite' => 'Club',
                    'deck_id' => '1',
                    'path' => 'deckdefault/p1.png'
                  ]);
            DB::table('cards')->insert([
                    'value' => '2',
                    'suite' => 'Club',
                    'deck_id' => '1',
                    'path' => 'deckdefault/p2.png'
                  ]);
            DB::table('cards')->insert([
                    'value' => '3',
                    'suite' => 'Club',
                    'deck_id' => '1',
                    'path' => 'deckdefault/p3.png'
                  ]);
            DB::table('cards')->insert([
                    'value' => '4',
                    'suite' => 'Club',
                    'deck_id' => '1',
                    'path' => 'deckdefault/p4.png'
                  ]);

            DB::table('cards')->insert([
                    'value' => '5',
                    'suite' => 'Club',
                    'deck_id' => '1',
                    'path' => 'deckdefault/p5.png'
                  ]);

            DB::table('cards')->insert([
                    'value' => '6',
                    'suite' => 'Club',
                    'deck_id' => '1',
                    'path' => 'deckdefault/p6.png'
                  ]);

            DB::table('cards')->insert([
                    'value' => '7',
                    'suite' => 'Club',
                    'deck_id' => '1',
                    'path' => 'deckdefault/p7.png'
                  ]);

              DB::table('cards')->insert([
                      'value' => 'Jack',
                      'suite' => 'Club',
                      'deck_id' => '1',
                      'path' => 'deckdefault/p11.png'
                    ]);

              DB::table('cards')->insert([
                      'value' => 'Queen',
                      'suite' => 'Club',
                      'deck_id' => '1',
                      'path' => 'deckdefault/p12.png'
                    ]);

              DB::table('cards')->insert([
                      'value' => 'King',
                      'suite' => 'Club',
                      'deck_id' => '1',
                      'path' => 'deckdefault/p13.png'
                    ]);

            }
        }
