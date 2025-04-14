<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $users = [
            [
                'name' => 'Usuário',
                'email' => 'user@user',
                'cpf_cnpj' => '13754674412',
                'phone' => '83991236636',
                'password' => Hash::make('@123Mudar123'),
            ],
            [
                'name' => 'Usuário 2',
                'email' => 'user2@user',
                'cpf_cnpj' => '13754674413',
                'phone' => '83991236636',
                'password' => Hash::make('@123Mudar123'),
            ]
        ];

        foreach($users as $indice => $user){
            $user = User::updateOrCreate(
                attributes: ['email' => $user['email']],
                values: $user
            );

            UserPlan::updateOrCreate([
                'user_id' => $user->id,
                'subscription_plan_id' => $indice + 1,
                'is_active' => true,
            ]);
        }
        
    }
}
