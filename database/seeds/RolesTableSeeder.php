<?php

use Illuminate\Database\Seeder;
use Myshop\Application\Service\RoleService;
use Myshop\Common\Model\DomainPermission;
use Myshop\Common\Model\DomainRole;

class RolesTableSeeder extends Seeder
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function run()
    {
        $this->command->line('>>> 역할 기준 정보를 만듭니다.');
        $this->roleService->createRole(DomainRole::ADMIN(), [DomainPermission::MANAGE_USER()]);
        $this->roleService->createRole(DomainRole::MEMBER(), [
            DomainPermission::MANAGE_PRODUCT(),
            DomainPermission::MANAGE_REVIEW()
        ]);
        $this->roleService->createRole(DomainRole::USER());
        $this->command->line('=> 역할 기준 정보를 만들었습니다.');
    }
}
