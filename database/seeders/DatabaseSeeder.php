<?php

namespace Database\Seeders;

use Carbon\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Inserarea unui utilizator folosind date predefinite
        /*DB::table('users')->insert([
            'name' => 'Sabina',
            'email' => 'sabina@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);*/

        $users = \App\Models\User::factory()->count(20)->create();      //-- Inserarea in baza de date a 20 de utilizatori cu informatii random

        $posts = \App\Models\BlogPost::factory()->count(50)->make()->each(function($post) use ($users) {
            $post->user_id = $users->random()->id;
            $post->save();
        });

        $comments = \App\Models\Comment::factory()->count(150)->make()->each(function($comment) use ($posts) {
            $comment->blog_post_id = $posts->random()->id;
            $comment->save();
        });
    }
}
