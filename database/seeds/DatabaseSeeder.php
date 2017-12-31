<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('>>> 데이터 시딩을 시작합니다.');
        $this->resetSchema();
        $this->unguardForeignKeyChecks();

        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);

        if (false === App::environment('production')) {
            $this->call(TestSeeder::class);
        }

        $this->reguardForeignKeyChecks();
        $this->command->info('>>> 데이터 시딩을 마칩니다.');
    }

    private function unguardForeignKeyChecks()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    }

    private function reguardForeignKeyChecks()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    private function resetSchema()
    {
        $confirmed = $this->command->confirm('테이블 스키마를 리셋하시겠습니까? 데이터도 같이 삭제됩니다. 리셋하지 않으면 외래키 제약조건 오류가 발생할 수 있습니다.');

        if ($confirmed) {
            $this->command->call('migrate:refresh');
            $this->command->warn('=> 테이블 테이터를 초기화했습니다.');
        }
    }
}
