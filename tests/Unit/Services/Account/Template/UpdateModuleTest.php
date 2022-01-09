<?php

namespace Tests\Unit\Services\Account\Template;

use Tests\TestCase;
use App\Models\User;
use App\Models\Module;
use App\Models\Account;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Account\ManageTemplate\UpdateModule;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateModuleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_module(): void
    {
        $ross = $this->createAdministrator();
        $module = $this->createModule($ross->account);
        $this->executeService($ross, $ross->account, $module);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateModule)->execute($request);
    }

    /** @test */
    public function it_fails_if_user_doesnt_belong_to_account(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $ross = $this->createAdministrator();
        $account = $this->createAccount();
        $module = $this->createModule($ross->account);
        $this->executeService($ross, $account, $module);
    }

    /** @test */
    public function it_fails_if_module_doesnt_belong_to_account(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $ross = $this->createAdministrator();
        $account = Account::factory()->create();
        $module = $this->createModule($account);
        $this->executeService($ross, $ross->account, $module);
    }

    private function executeService(User $author, Account $account, Module $module): void
    {
        Queue::fake();

        $request = [
            'account_id' => $account->id,
            'author_id' => $author->id,
            'module_id' => $module->id,
            'name' => 'name',
        ];

        (new UpdateModule)->execute($request);

        $this->assertDatabaseHas('modules', [
            'id' => $module->id,
            'account_id' => $account->id,
            'name' => 'name',
        ]);

        $this->assertInstanceOf(
            Module::class,
            $module
        );
    }

    private function createModule(Account $account): Module
    {
        $module = Module::factory()->create([
            'account_id' => $account->id,
        ]);

        return $module;
    }
}
