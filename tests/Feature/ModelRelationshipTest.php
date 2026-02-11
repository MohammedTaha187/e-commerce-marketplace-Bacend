<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Conversation;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    public function test_user_has_addresses()
    {
        $user = new User;
        $this->assertInstanceOf(HasMany::class, $user->addresses());
        $this->assertEquals(Address::class, $user->addresses()->getRelated()::class);
    }

    public function test_user_has_vendor_order_items()
    {
        $user = new User;
        $this->assertInstanceOf(HasMany::class, $user->vendorOrderItems());
        $this->assertEquals(OrderItem::class, $user->vendorOrderItems()->getRelated()::class);
    }

    public function test_address_belongs_to_user_and_morphs_to_target()
    {
        $address = new Address;
        $this->assertInstanceOf(BelongsTo::class, $address->user());
        $this->assertInstanceOf(MorphTo::class, $address->target());
    }

    public function test_product_variant_belongs_to_product()
    {
        $variant = new ProductVariant;
        $this->assertInstanceOf(BelongsTo::class, $variant->product());
    }

    public function test_coupon_has_orders()
    {
        $coupon = new Coupon;
        $this->assertInstanceOf(HasMany::class, $coupon->orders());
    }

    public function test_order_has_timeline()
    {
        $order = new Order;
        $this->assertInstanceOf(HasMany::class, $order->timeline());
    }

    public function test_order_item_has_refunds()
    {
        $item = new OrderItem;
        $this->assertInstanceOf(HasMany::class, $item->refunds());
    }

    public function test_conversation_scope_for_user()
    {
        // Testing scope existence
        $this->assertTrue(method_exists(Conversation::class, 'scopeForUser'));
    }
}
