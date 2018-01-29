<?php

use App\Support\KoreanLoremProvider;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Myshop\Application\Service\RoleService;
use Myshop\Common\Model\Money;
use Myshop\Common\Model\DomainRole;
use Myshop\Domain\Model\Product;
use Myshop\Domain\Model\Review;
use Myshop\Domain\Model\User;
use Myshop\Domain\Repository\RoleRepository;

class TestSeeder extends Seeder
{
    private $roleRepository;
    private $roleService;
    private $faker;

    public function __construct(
        RoleRepository $roleRepository,
        RoleService $roleService
    ) {
        $this->roleRepository = $roleRepository;
        $this->roleService = $roleService;
        $this->faker = $this->getFaker();
    }

    public function run()
    {
        $this->command->line('>>> 새 사용자를 만듭니다.');
        $this->createUser('Admin', 'admin@example.com', DomainRole::ADMIN());
        $this->createUser('Member', 'member@example.com', DomainRole::MEMBER());
        $this->createUser('User', 'user@example.com', DomainRole::USER());
        $this->createUser('Stranger', 'stranger@example.com', DomainRole::MEMBER(), 'secret', ['10.10.10.10/32']);
        $this->command->line('=> 새 사용자를 4명을 만들었습니다.');

        $this->command->line('>>> 새 상품을 만듭니다.');
        $this->createProduct($noOfProducts = 10000);
        $this->command->line("=> 새 상품 {$noOfProducts}개를 만들었습니다.");

        $this->command->line('>>> 새 리뷰를 만듭니다.');
        $this->createReview($noOfReviews = 1);
        $this->command->line("=> 각 상품당 {$noOfReviews}개씩의 새 리뷰를 만들었습니다.");
    }

    private function getFaker()
    {
        $faker = Factory::create('ko_KR');
        $koreanProvider = new KoreanLoremProvider($faker);
        $faker->addProvider($koreanProvider);

        return $faker;
    }

    private function createUser(
        string $name,
        string $email,
        DomainRole $roleName,
        string $password = 'secret',
        array $allowedIps = ['*']
    ) {
        $user = User::forceCreate([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'allowed_ips' => $allowedIps,
        ]);

        $role = $this->roleRepository->findByName($roleName);
        $this->roleService->assignRoleToUser($user, $role);
    }

    private function createProduct($noOfProducts = 1)
    {
        foreach (range(1, $noOfProducts) as $index) {
            Product::forceCreate([
                'title' => $this->faker->korSentence(3),
                'stock' => rand(1, 5),
                'price' => new Money(rand(1, 10) * 1000),
                'description' => $this->faker->korParagraph(2),
            ]);
        }
    }

    private function createReview($noOfReviews = 1)
    {
        $users = User::all();

        Product::all()->each(function (Product $product) use ($users, $noOfReviews) {
            foreach (range(1, $noOfReviews) as $index) {
                /** @var User $reviewAuthor */
                $reviewAuthor = $users->random();
                Review::forceCreate([
                    'user_id' => $reviewAuthor->id,
                    'product_id' => $product->id,
                    'title' => $this->faker->korSentence(3),
                    'content' => $this->faker->korParagraph(2),
                ]);
            }
        });
    }
}
