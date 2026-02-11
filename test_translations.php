<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SellerProfile;
use App\Models\User;
use Illuminate\Support\Facades\App;

// Create a user for the seller profile
$user = User::factory()->create();

// Create a brand for the product
$brand = Brand::create(['name' => 'Apple', 'slug' => 'apple']);

// 1. Test Category Translation
echo "Testing Category Translation...\n";
$category = Category::create(['image' => 'test-image.jpg']);

// Add English translation
$category->translations()->create([
    'locale' => 'en',
    'name' => 'Electronics',
    'slug' => 'electronics',
    'description' => 'Electronic items',
]);

// Add Arabic translation
$category->translations()->create([
    'locale' => 'ar',
    'name' => 'إلكترونيات',
    'slug' => 'electronics-ar',
    'description' => 'عناصر إلكترونية',
]);

// Refresh model to load relationships
$category->load('translations');

// Set locale to 'en' and check
App::setLocale('en');
echo 'EN Name: '.$category->name." (Expected: Electronics)\n";

// Set locale to 'ar' and check
App::setLocale('ar');
echo 'AR Name: '.$category->name." (Expected: إلكترونيات)\n";

// Test fallback (assuming 'en' is fallback)
App::setLocale('fr');
echo 'FR Name (Fallback): '.$category->name." (Expected: Electronics)\n";

echo "--------------------------------------------------\n";

// 2. Test Product Translation
echo "Testing Product Translation...\n";
$product = Product::create([
    'category_id' => $category->id,
    'brand_id' => $brand->id,
    'seller_id' => $user->id, // Fixed: use created user id
    'price' => 100,
    'stock' => 10,
    'image' => 'product.jpg',
]);

$product->translations()->create([
    'locale' => 'en',
    'title' => 'iPhone 15',
    'slug' => 'iphone-15',
    'description' => 'Latest iPhone',
]);

$product->translations()->create([
    'locale' => 'ar',
    'title' => 'أيفون 15',
    'slug' => 'iphone-15-ar',
    'description' => 'أحدث أيفون',
]);

$product->load('translations');

App::setLocale('en');
echo 'EN Title: '.$product->title." (Expected: iPhone 15)\n";

App::setLocale('ar');
echo 'AR Title: '.$product->title." (Expected: أيفون 15)\n";

echo "--------------------------------------------------\n";

// 3. Test SellerProfile Translation
echo "Testing SellerProfile Translation...\n";
$profile = SellerProfile::create([
    'user_id' => $user->id,
    'store_logo' => 'logo.jpg',
    'banner_image' => 'banner.jpg',
    'bank_account_details' => '123456',
    'commission_rate' => 10,
]);

$profile->translations()->create([
    'locale' => 'en',
    'store_name' => 'Tech Store',
    'store_description' => 'Best tech store',
]);

$profile->translations()->create([
    'locale' => 'ar',
    'store_name' => 'متجر التقنية',
    'store_description' => 'أفضل متجر تقني',
]);

$profile->load('translations');

App::setLocale('en');
echo 'EN Store: '.$profile->store_name." (Expected: Tech Store)\n";

App::setLocale('ar');
echo 'AR Store: '.$profile->store_name." (Expected: متجر التقنية)\n";

echo "Done.\n";
