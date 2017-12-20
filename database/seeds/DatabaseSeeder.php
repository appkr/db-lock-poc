<?php

use App\Support\KoreanLoremProvider;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Myshop\Common\Model\Money;
use Myshop\Domain\Model\Product;
use Myshop\Domain\Model\Review;
use Myshop\Domain\Model\User;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('데이터 시딩을 시작합니다.');

        $faker = Factory::create('ko_KR');
        $koreanProvider = new KoreanLoremProvider($faker);
        $faker->addProvider($koreanProvider);

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $this->command->error('기존 데이터를 지웁니다.');
        User::truncate();
        Product::truncate();
        Review::truncate();

        $this->command->warn('새 사용자를 생성합니다.');
        $member = User::forceCreate([
            'name' => 'Member',
            'email' => 'member@example.com',
            'password' => bcrypt('secret'),
        ]);

        $user = User::forceCreate([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('secret'),
        ]);
        $this->command->line('member@example.com, user@example.com 사용자를 생성했습니다.');

        $this->command->warn('새 상품을 생성합니다.');
        foreach (range(0, 10) as $index) {
            Product::forceCreate([
                'title' => $faker->korSentence(3),
                'stock' => rand(1, 5),
                'price' => new Money(rand(1, 10) * 1000),
                'description' => $faker->korParagraph(2),
            ]);
        }
        $this->command->line('새 상품 10개를 생성했습니다.');

        $this->command->warn('새 리뷰를 생성합니다.');
        Product::all()->each(function (Product $product) use ($member, $user, $faker) {
            $user = [$member, $user][rand(0, 1)];

            Review::forceCreate([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'title' => $faker->korSentence(3),
                'content' => $faker->korParagraph(2),
            ]);
        });
        $this->command->line('각 상품당 1개씩의 새 리뷰를 생성했습니다.');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $this->command->info('데이터 시딩을 마칩니다.');
    }
}
