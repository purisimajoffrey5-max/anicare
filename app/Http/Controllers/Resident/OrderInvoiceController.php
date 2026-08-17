<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class OrderInvoiceController extends Controller
{
    /**
     * Make sure the logged-in user is the resident who owns the order.
     */
    private function getResidentOrder(int $id): Order
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'resident') {
            abort(403, 'Unauthorized');
        }

        return Order::with([
                'product',
                'farmer',
                'resident',
            ])
            ->where('id', $id)
            ->where('resident_id', $user->id)
            ->firstOrFail();
    }

    /**
     * Page shown immediately after a successful checkout.
     */
    public function success(int $order)
    {
        $orderModel = $this->getResidentOrder($order);

        return view('resident.orders.success', [
            'invoice' => $this->invoiceData($orderModel),
        ]);
    }

    /**
     * View invoice in the browser.
     */
    public function show(int $order)
    {
        $orderModel = $this->getResidentOrder($order);

        return view('resident.orders.invoice', [
            'invoice' => $this->invoiceData($orderModel),
        ]);
    }

    /**
     * Download invoice as PDF.
     */
    public function download(int $order)
    {
        $orderModel = $this->getResidentOrder($order);

        $invoice = $this->invoiceData($orderModel);

        $pdf = Pdf::loadView(
            'resident.orders.invoice_pdf',
            compact('invoice')
        )->setPaper('a4', 'portrait');

        return $pdf->download(
            $invoice['invoice_number'] . '.pdf'
        );
    }

    /**
     * Prepare one consistent set of data for:
     * - success page
     * - invoice page
     * - PDF invoice
     */
    private function invoiceData(Order $order): array
    {
        $product = $order->product;
        $farmer = $order->farmer;
        $resident = $order->resident;

        $createdAt = $order->created_at ?? now();

        $invoiceNumber =
            'ANI-' .
            $createdAt->format('Ymd') .
            '-' .
            str_pad(
                (string) $order->id,
                6,
                '0',
                STR_PAD_LEFT
            );

        /*
        |--------------------------------------------------------------------------
        | PURCHASE UNIT
        |--------------------------------------------------------------------------
        */
        $purchaseUnit =
            in_array(
                $order->purchase_unit,
                ['sack', 'kilo'],
                true
            )
                ? $order->purchase_unit
                : 'kilo';

        /*
        |--------------------------------------------------------------------------
        | QUANTITY
        |--------------------------------------------------------------------------
        */
        $quantityKilos =
            (float) (
                $order->quantity_kilos
                ?? $order->total_kilos
                ?? 0
            );

        $quantitySacks =
            (float) (
                $order->quantity_sacks
                ?? (
                    $quantityKilos > 0
                        ? $quantityKilos / 60
                        : 0
                )
            );

        /*
        |--------------------------------------------------------------------------
        | PRICE
        |--------------------------------------------------------------------------
        */
        $pricePerKg =
            (float) (
                $order->price_per_kg
                ?? $order->unit_price
                ?? $product?->price_per_kg
                ?? 0
            );

        $pricePerSack =
            (float) (
                $order->price_per_sack
                ?? $product?->price_per_sack
                ?? $product?->price_per_cavan
                ?? round($pricePerKg * 60)
            );

        /*
        |--------------------------------------------------------------------------
        | QUANTITY DISPLAY + UNIT PRICE
        |--------------------------------------------------------------------------
        */
        if ($purchaseUnit === 'sack') {
            $quantityDisplay =
                number_format(
                    $quantitySacks,
                    0
                ) .
                (
                    $quantitySacks == 1
                        ? ' sack / cavan'
                        : ' sacks / cavan'
                );

            $unitPrice =
                $pricePerSack;

            $unitPriceLabel =
                'per sack / cavan';
        } else {
            $quantityDisplay =
                number_format(
                    $quantityKilos,
                    2
                ) .
                ' kg';

            $unitPrice =
                $pricePerKg;

            $unitPriceLabel =
                'per kg';
        }

        /*
        |--------------------------------------------------------------------------
        | TOTALS
        |--------------------------------------------------------------------------
        */
        $subtotal =
            (float) (
                $order->subtotal
                ?? $order->total_price
                ?? 0
            );

        if ($subtotal <= 0) {
            $subtotal =
                $purchaseUnit === 'sack'
                    ? $quantitySacks * $pricePerSack
                    : $quantityKilos * $pricePerKg;
        }

        $shippingFee =
            (float) (
                $order->shipping_fee
                ?? 0
            );

        $grandTotal =
            (float) (
                $order->grand_total
                ?? ($subtotal + $shippingFee)
            );

        /*
        |--------------------------------------------------------------------------
        | PEOPLE / SNAPSHOTS
        |--------------------------------------------------------------------------
        */
        $buyerName =
            $order->buyer_name_snapshot
            ?? $order->buyer_name
            ?? $resident?->fullname
            ?? $resident?->username
            ?? 'Resident';

        $buyerContact =
            $order->buyer_contact_snapshot
            ?? $order->contact_number
            ?? $resident?->mobile_number
            ?? $resident?->contact_number
            ?? $resident?->phone
            ?? '';

        $buyerAddress =
            $order->buyer_address_snapshot
            ?? $order->delivery_address
            ?? $resident?->address
            ?? '';

        $farmerName =
            $order->farmer_name_snapshot
            ?? $farmer?->fullname
            ?? $farmer?->username
            ?? 'Unknown Farmer';

        $farmerAddress =
            $order->farmer_address_snapshot
            ?? $order->pickup_address
            ?? $farmer?->address
            ?? 'Farmer address not available';

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */
        $fulfillmentType =
            strtolower(
                (string) (
                    $order->fulfillment_type
                    ?? 'delivery'
                )
            );

        $paymentMethod =
            strtolower(
                (string) (
                    $order->payment_method
                    ?? 'cash'
                )
            );

        $paymentStatus =
            strtolower(
                (string) (
                    $order->payment_status
                    ?? 'unpaid'
                )
            );

        $orderStatus =
            strtolower(
                (string) (
                    $order->status
                    ?? 'pending'
                )
            );

        return [
            'order_id' =>
                $order->id,

            'invoice_number' =>
                $invoiceNumber,

            'order_number' =>
                $order->order_number
                ?? $order->reference_number
                ?? $invoiceNumber,

            'date' =>
                $createdAt->format('F d, Y'),

            'time' =>
                $createdAt->format('h:i A'),

            'buyer_name' =>
                $buyerName,

            'buyer_contact' =>
                $buyerContact,

            'buyer_address' =>
                $buyerAddress,

            'farmer_name' =>
                $farmerName,

            'farmer_address' =>
                $farmerAddress,

            'product_name' =>
                $order->product_name_snapshot
                ?? $product?->name
                ?? 'Rice / Palay Product',

            'purchase_unit' =>
                $purchaseUnit,

            'quantity_display' =>
                $quantityDisplay,

            'quantity_sacks' =>
                $quantitySacks,

            'quantity_kilos' =>
                $quantityKilos,

            'unit_price' =>
                $unitPrice,

            'unit_price_label' =>
                $unitPriceLabel,

            'price_per_kg' =>
                $pricePerKg,

            'price_per_sack' =>
                $pricePerSack,

            'subtotal' =>
                $subtotal,

            'shipping_fee' =>
                $shippingFee,

            'grand_total' =>
                $grandTotal,

            'distance_km' =>
                (float) (
                    $order->distance_km
                    ?? 0
                ),

            'fulfillment_type' =>
                $fulfillmentType,

            'delivery_address' =>
                $order->delivery_address
                ?? $buyerAddress,

            'pickup_address' =>
                $order->pickup_address
                ?? $farmerAddress,

            'payment_method' =>
                $paymentMethod,

            'payment_status' =>
                $paymentStatus,

            'order_status' =>
                $orderStatus,

            'notes' =>
                $order->notes_snapshot
                ?? $order->notes
                ?? null,
        ];
    }
}