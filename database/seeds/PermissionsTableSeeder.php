<?php

use Illuminate\Database\Seeder;
use Myshop\Application\Service\PermissionService;

class PermissionsTableSeeder extends Seeder
{
    private $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function run()
    {
        $this->command->line('>>> 권한 기준 정보를 만듭니다.');
        $this->permissionService->createAllPermissions();
        $this->command->line('=> 권한 기준 정보를 만들었습니다.');
    }
}
