<?php

// Đường dẫn: C:\xampp\htdocs\security_evaluation_system\01_backend\database\factories\DeviceFactory.php

namespace Database\Factories;

use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceFactory extends Factory
{
    protected $model = Device::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word(),
            'type' => $this->faker->randomElement(['mobile', 'desktop', 'laptop', 'server']),
            'status' => $this->faker->randomElement(['active', 'inactive', 'pending']),
            'mac_address' => $this->faker->macAddress(),
            'ip_address' => $this->faker->ipv4(),
            'last_seen' => $this->faker->dateTime(),
        ];
    }
}