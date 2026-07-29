<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Services\CategoryService;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CategorySystemDefaultTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_user_gets_default_categories_automatically()
    {
        $user = User::factory()->create();

        $systemCategoriesCount = Category::where('user_id', $user->id)
            ->where('is_system', true)
            ->count();

        $this->assertEquals(12, $systemCategoriesCount, 'New user should get 12 default system categories');
    }

    public function test_category_seeder_is_idempotent()
    {
        $user = User::factory()->create();

        // Count categories after creation (auto-seeded)
        $initialCount = Category::where('user_id', $user->id)->count();

        // Run seeder again
        $this->seed(CategorySeeder::class);

        // Count again
        $afterSeedCount = Category::where('user_id', $user->id)->count();

        $this->assertEquals($initialCount, $afterSeedCount, 'Seeder must be idempotent and not create duplicate categories');
    }

    public function test_user_can_create_new_personal_category()
    {
        $user = User::factory()->create();
        $service = app(CategoryService::class);

        $category = $service->createCategory([
            'name' => 'Hobi Baru',
            'color' => '#ffffff',
            'type' => 'expense'
        ], $user->id);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Hobi Baru',
            'is_system' => false
        ]);
    }

    public function test_user_cannot_create_category_with_system_name_case_insensitive()
    {
        $user = User::factory()->create();
        $service = app(CategoryService::class);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Kategori ini sudah disediakan oleh sistem.');

        // Try to create category with same name as system but different case
        $service->createCategory([
            'name' => 'tAbUnGaN', // 'Tabungan' is system category
            'color' => '#ffffff',
            'type' => 'expense'
        ], $user->id);
    }

    public function test_user_cannot_delete_system_category()
    {
        $user = User::factory()->create();
        $service = app(CategoryService::class);

        $systemCategory = Category::where('user_id', $user->id)->where('is_system', true)->first();

        $result = $service->deleteCategory($systemCategory->id, $user->id);

        $this->assertFalse($result['success']);
        $this->assertEquals('Kategori sistem tidak dapat dihapus.', $result['message']);
        
        $this->assertDatabaseHas('categories', [
            'id' => $systemCategory->id
        ]);
    }

    public function test_user_cannot_edit_system_category()
    {
        $user = User::factory()->create();
        $service = app(CategoryService::class);

        $systemCategory = Category::where('user_id', $user->id)->where('is_system', true)->first();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Kategori sistem tidak dapat diedit.');

        $service->updateCategory($systemCategory->id, [
            'name' => 'Ubah Nama',
            'color' => '#000000',
            'type' => 'expense'
        ]);
    }

    public function test_user_can_edit_and_delete_personal_category()
    {
        $user = User::factory()->create();
        $service = app(CategoryService::class);

        // Create personal category
        $category = $service->createCategory([
            'name' => 'Kategori Pribadi',
            'color' => '#ffffff',
            'type' => 'expense'
        ], $user->id);

        // Edit
        $service->updateCategory($category->id, [
            'name' => 'Kategori Pribadi Diedit',
            'color' => '#000000',
            'type' => 'expense'
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Kategori Pribadi Diedit',
        ]);

        // Delete
        $result = $service->deleteCategory($category->id, $user->id);

        $this->assertTrue($result['success']);
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }
}
