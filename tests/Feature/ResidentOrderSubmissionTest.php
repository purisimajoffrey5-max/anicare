<?php

namespace Tests\Feature;

use App\Models\RiceProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidentOrderSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_can_place_order_with_checkout_form_payload(): void
    {
        $farmer = User::factory()->create([
            'role' => 'farmer',
            'is_approved' => true,
        ]);

        $resident = User::factory()->create([
            'role' => 'resident',
            'is_approved' => true,
        ]);

        $product = RiceProduct::create([
            'user_id' => $farmer->id,
            'name' => 'Premium Rice',
            'type' => 'rice',
            'price_per_kg' => 50,
            'kilos_available' => 200,
            'photo_path' => null,
            'is_active' => true,
        ]);

        $this->actingAs($resident);

        $response = $this->post(route('resident.checkout.place', $product->id), [
            'buyer_name' => 'Juan Dela Cruz',
            'contact_number' => '09123456789',
            'quantity_sacks' => 2,
            'fulfillment_type' => 'delivery',
            'delivery_address' => '123 Example St, San Isidro',
            'pickup_address' => '',
            'shipping_fee' => '120',
            'distance_km' => '5',
            'delivery_latitude' => '14.1234',
            'delivery_longitude' => '121.5678',
            'payment_method' => 'cash',
            'notes' => 'Please deliver before 5pm',
        ]);

        $response->assertRedirect(route('resident.orders.index'));

        $this->assertDatabaseHas('orders', [
            'resident_id' => $resident->id,
            'rice_product_id' => $product->id,
            'buyer_name' => 'Juan Dela Cruz',
            'contact_number' => '09123456789',
            'fulfillment_type' => 'delivery',
            'payment_method' => 'cash',
            'delivery_address' => '123 Example St, San Isidro',
        ]);

        $this->assertDatabaseHas('orders', [
            'quantity_kilos' => '120.00',
        ]);
    }
}
