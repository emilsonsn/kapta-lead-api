<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        
        $channels = [
            [
                'name' => 'Instagram',
                'description' => "Link do instagram",
                'image' => "https://png.pngtree.com/png-clipart/20221019/original/pngtree-instagram-social-platform-icon-png-image_8704818.png",
            ],
            [
                'name' => 'Meu site',
                'description' => "Site pessoal",
                'image' => "https://cdn-icons-png.flaticon.com/512/5339/5339184.png",
            ],            
        ];

        User::get()->each(function($user) use ($channels){
            foreach($channels as $channel){
                $channel['user_id'] = $user->id;
                Channel::updateOrCreate(
                    attributes: $channel,
                    values: $channel
                );
            }
        });
    }
}
