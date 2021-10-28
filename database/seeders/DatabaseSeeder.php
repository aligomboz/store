<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use App\Models\ProductCoupon;
use App\Models\ShippingCompany;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            // EntrustSeeder::class,
            // ProductCategorySeeder::class,
            // TagSeeder::class,
            // ProductSeeder::class,
            // ProductTagsSeeder::class,
            // ProductImagesSeeder::class,
            // ProductCouponSeeder::class,
            // ProductReviewSeeder::class,
            // PaymentMethodSeeder::class,
            WorldSeeder::class,
            WorldStatusSeeder::class,
            ShippingCompanySeeder::class
        ]);
    }
}
