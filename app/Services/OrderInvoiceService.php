<?php

namespace App\Services;

use App\Models\Order;
use App\Models\RiceProduct;
use App\Services\VatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class OrderInvoiceService
{
    /**
     * Save a transparent invoice/order snapshot without breaking
     * older databases that may not yet contain every invoice column.
     */
    public function capture(
        Order $order,
        Request $request,
        RiceProduct $product
    ): Order {
        $kgPerCavan = 60.0;

        /*
        |--------------------------------------------------------------------------
        | PURCHASE UNIT / QUANTITY
        |--------------------------------------------------------------------------
        */

        $purchaseUnit = strtolower(
            (string) $request->input('purchase_unit', 'kilo')
        );

        if (!in_array($purchaseUnit, ['sack', 'kilo'], true)) {
            $purchaseUnit = 'kilo';
        }

        $quantityKilos = (float) (
            $request->input('quantity_kilos')
            ?? $request->input('total_kilos')
            ?? $order->quantity_kilos
            ?? 0
        );

        $quantitySacks = (float) (
            $request->input('quantity_sacks')
            ?? (
                $quantityKilos > 0
                    ? $quantityKilos / $kgPerCavan
                    : 0
            )
        );

        /*
        |--------------------------------------------------------------------------
        | PRICES
        |--------------------------------------------------------------------------
        */

        $pricePerKg = (float) (
            $product->price_per_kg
            ?? $order->unit_price
            ?? 0
        );

        $pricePerSack = (float) (
            $product->price_per_sack
            ?? $product->price_per_cavan
            ?? round($pricePerKg * $kgPerCavan)
        );

        /*
        |--------------------------------------------------------------------------
        | SUBTOTAL
        |--------------------------------------------------------------------------
        |
        | OrderController already stores product subtotal in total_price.
        | Recalculate only as a fallback.
        |
        */

        $subtotal = (float) ($order->total_price ?? 0);

        if ($subtotal <= 0) {
            $subtotal =
                $purchaseUnit === 'sack'
                    ? $quantitySacks * $pricePerSack
                    : $quantityKilos * $pricePerKg;
        }

        /*
        |--------------------------------------------------------------------------
        | SHIPPING / TOTAL
        |--------------------------------------------------------------------------
        */

        $shippingFee = max(
            0,
            (float) $request->input('shipping_fee', 0)
        );

        if (($order->fulfillment_type ?? 'delivery') === 'pickup') {
            $shippingFee = 0.0;
        }

        $distanceKm = max(
            0,
            (float) $request->input('distance_km', 0)
        );

        if (($order->fulfillment_type ?? 'delivery') === 'pickup') {
            $distanceKm = 0.0;
        }

        $grandTotal = $subtotal + $shippingFee;

        /*
        |--------------------------------------------------------------------------
        | VAT SNAPSHOT
        |--------------------------------------------------------------------------
        | Read the current Admin VAT setting server-side. The VAT is inclusive,
        | so the charged grand total remains the same.
        */
        $vat = VatService::breakdown($grandTotal);

        /*
        |--------------------------------------------------------------------------
        | INVOICE NUMBER
        |--------------------------------------------------------------------------
        */

        $date = $order->created_at ?? now();

        $invoiceNumber =
            'ANI-' .
            $date->format('Ymd') .
            '-' .
            str_pad(
                (string) $order->id,
                6,
                '0',
                STR_PAD_LEFT
            );

        /*
        |--------------------------------------------------------------------------
        | BUYER SNAPSHOT
        |--------------------------------------------------------------------------
        */

        $resident = $order->resident;

        $buyerName = trim((string) (
            $order->buyer_name
            ?? $resident?->fullname
            ?? $resident?->username
            ?? 'Resident'
        ));

        $buyerContact = trim((string) (
            $order->contact_number
            ?? $resident?->mobile_number
            ?? $resident?->contact_number
            ?? $resident?->phone
            ?? ''
        ));

        $buyerAddress = trim((string) (
            $order->delivery_address
            ?? $resident?->address
            ?? $resident?->complete_address
            ?? ''
        ));

        /*
        |--------------------------------------------------------------------------
        | FARMER SNAPSHOT
        |--------------------------------------------------------------------------
        */

        $farmer = $product->user ?? $order->farmer;

        $farmerName = trim((string) (
            $farmer?->fullname
            ?? $farmer?->username
            ?? 'Unknown Farmer'
        ));

        $farmerAddress = trim((string) (
            $order->pickup_address
            ?? $farmer?->address
            ?? $farmer?->complete_address
            ?? ''
        ));

        /*
        |--------------------------------------------------------------------------
        | DATA TO SAVE
        |--------------------------------------------------------------------------
        |
        | We filter these values using Schema::hasColumn() so this service
        | remains compatible even if some optional invoice columns have not
        | been added to the orders table yet.
        |
        */

        $candidateValues = [
            'invoice_number' => $invoiceNumber,

            'purchase_unit' => $purchaseUnit,

            'quantity_sacks' => round($quantitySacks, 4),
            'quantity_kilos' => round($quantityKilos, 2),
            'total_kilos' => round($quantityKilos, 2),

            'price_per_kg' => round($pricePerKg, 2),
            'price_per_sack' => round($pricePerSack, 2),

            'subtotal' => round($subtotal, 2),
            'shipping_fee' => round($shippingFee, 2),
            'grand_total' => round($grandTotal, 2),
            'vat_enabled' => $vat['enabled'],
            'vat_rate' => $vat['rate'],
            'vatable_sales' => $vat['vatable_sales'],
            'vat_amount' => $vat['vat'],
            'total_sales' => $vat['total_sales'],
            'distance_km' => round($distanceKm, 2),

            'buyer_name_snapshot' => $buyerName,
            'buyer_contact_snapshot' => $buyerContact,
            'buyer_address_snapshot' => $buyerAddress,

            'farmer_name_snapshot' => $farmerName,
            'farmer_address_snapshot' => $farmerAddress,

            'product_name_snapshot' => (string) (
                $product->name
                ?? 'Rice / Palay Product'
            ),

            'notes_snapshot' => $order->notes,

            /*
             * Invoice means order confirmation, not proof of payment.
             * Payment remains unpaid until the farmer confirms receipt.
             */
            'payment_status' => $order->payment_status ?: 'unpaid',
        ];

        $updates = [];

        foreach ($candidateValues as $column => $value) {
            if (Schema::hasColumn('orders', $column)) {
                $updates[$column] = $value;
            }
        }

        if (!empty($updates)) {
            /*
             * forceFill is intentional because these snapshot columns may not
             * be present in the Order model's $fillable array.
             */
            $order->forceFill($updates);
            $order->save();
        }

        return $order->fresh([
            'product',
            'farmer',
            'resident',
        ]);
    }
}