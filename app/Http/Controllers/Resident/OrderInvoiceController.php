<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderInvoiceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GET RESIDENT ORDER
    |--------------------------------------------------------------------------
    */

    private function getResidentOrder(int $id): Order
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'resident') {
            abort(403, 'Unauthorized.');
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


    /*
    |--------------------------------------------------------------------------
    | SUCCESS PAGE
    |--------------------------------------------------------------------------
    */

    public function success(int $order)
    {
        $orderModel = $this->getResidentOrder($order);

        return view('resident.orders.success', [
            'invoice' => $this->invoiceData($orderModel),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | VIEW INVOICE
    |--------------------------------------------------------------------------
    */

    public function show(int $order)
    {
        $orderModel = $this->getResidentOrder($order);

        return view('resident.orders.invoice', [
            'invoice' => $this->invoiceData($orderModel),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | PREPARE PDF
    |--------------------------------------------------------------------------
    |
    | NEW FLOW:
    |
    | Invoice
    |    ↓
    | Generate PDF
    |    ↓
    | Save PDF to public/generated-invoices
    |    ↓
    | Show PDF READY page
    |    ↓
    | User chooses Open PDF or Download PDF
    |
    */

    public function download(int $order)
    {
        try {

            @set_time_limit(60);
            ini_set('memory_limit', '256M');


            /*
            |--------------------------------------------------------------------------
            | GET ORDER
            |--------------------------------------------------------------------------
            */

            $orderModel = $this->getResidentOrder($order);

            $invoice = $this->invoiceData($orderModel);


            /*
            |--------------------------------------------------------------------------
            | DIRECTORY
            |--------------------------------------------------------------------------
            */

            $directory = public_path('generated-invoices');

            if (!File::exists($directory)) {
                File::makeDirectory(
                    $directory,
                    0755,
                    true,
                    true
                );
            }


            /*
            |--------------------------------------------------------------------------
            | DELETE OLD PDF FILES
            |--------------------------------------------------------------------------
            |
            | Delete generated PDFs older than 1 hour.
            |
            */

            foreach (File::files($directory) as $oldFile) {

                if (
                    strtolower($oldFile->getExtension()) === 'pdf'
                    &&
                    $oldFile->getMTime() < now()->subHour()->timestamp
                ) {
                    File::delete(
                        $oldFile->getPathname()
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | RANDOM SECURE FILE NAME
            |--------------------------------------------------------------------------
            */

            $token = bin2hex(
                random_bytes(16)
            );

            $filename =
                $invoice['invoice_number']
                . '-'
                . $token
                . '.pdf';

            $filePath =
                $directory
                . DIRECTORY_SEPARATOR
                . $filename;


            /*
            |--------------------------------------------------------------------------
            | GENERATE PDF
            |--------------------------------------------------------------------------
            */

            $pdf = Pdf::loadView(
                'resident.orders.invoice_pdf',
                [
                    'invoice' => $invoice,
                ]
            );

            $pdf->setPaper(
                'a4',
                'portrait'
            );

            $pdf->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => false,
                'isJavascriptEnabled' => false,
                'isHtml5ParserEnabled' => true,
            ]);


            /*
            |--------------------------------------------------------------------------
            | SAVE PDF
            |--------------------------------------------------------------------------
            */

            $pdf->save(
                $filePath
            );

            clearstatcache(
                true,
                $filePath
            );


            /*
            |--------------------------------------------------------------------------
            | VERIFY FILE
            |--------------------------------------------------------------------------
            */

            if (!File::exists($filePath)) {

                throw new \RuntimeException(
                    'PDF file was not created.'
                );
            }


            $fileSize = File::size(
                $filePath
            );


            if ($fileSize < 100) {

                File::delete(
                    $filePath
                );

                throw new \RuntimeException(
                    'Generated PDF is empty.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | VERIFY PDF HEADER
            |--------------------------------------------------------------------------
            */

            $handle = fopen(
                $filePath,
                'rb'
            );

            if (!$handle) {

                throw new \RuntimeException(
                    'Generated PDF cannot be opened.'
                );
            }


            $header = fread(
                $handle,
                4
            );

            fclose(
                $handle
            );


            if ($header !== '%PDF') {

                File::delete(
                    $filePath
                );

                throw new \RuntimeException(
                    'Generated file is not a valid PDF.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | PDF URL
            |--------------------------------------------------------------------------
            |
            | RELATIVE URL para same domain:
            |
            | localhost kapag laptop
            | ngrok kapag cellphone
            |
            */

            $pdfUrl =
                '/generated-invoices/'
                . rawurlencode(
                    $filename
                );


            /*
            |--------------------------------------------------------------------------
            | LOG SUCCESS
            |--------------------------------------------------------------------------
            */

            Log::info(
                'ANI-CARE PDF successfully prepared.',
                [
                    'order_id' => $order,
                    'resident_id' => Auth::id(),
                    'filename' => $filename,
                    'size' => $fileSize,
                    'url' => $pdfUrl,
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | SHOW PDF READY PAGE
            |--------------------------------------------------------------------------
            */

            return view(
                'resident.orders.pdf-ready',
                [
                    'invoice' => $invoice,
                    'pdfUrl' => $pdfUrl,
                    'filename' => $filename,
                    'fileSize' => $fileSize,
                ]
            );


        } catch (Throwable $e) {

            Log::error(
                'ANI-CARE PDF generation failed.',
                [
                    'order_id' => $order,
                    'resident_id' => Auth::id(),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | SHOW ACTUAL PDF ERROR PAGE
            |--------------------------------------------------------------------------
            |
            | Hindi na tayo silent redirect pabalik sa invoice.
            |
            */

            return response()->view(
                'resident.orders.pdf-error',
                [
                    'orderId' => $order,
                    'message' => $e->getMessage(),
                ],
                500
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | INVOICE DATA
    |--------------------------------------------------------------------------
    */

    private function invoiceData(Order $order): array
    {
        $product = $order->product;

        $farmer = $order->farmer;

        $resident = $order->resident;

        $createdAt =
            $order->created_at
            ?? now();


        /*
        |--------------------------------------------------------------------------
        | INVOICE NUMBER
        |--------------------------------------------------------------------------
        */

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
                [
                    'sack',
                    'kilo',
                ],
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
        | PRICES
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
                ?? round(
                    $pricePerKg * 60
                )
            );


        /*
        |--------------------------------------------------------------------------
        | DISPLAY UNIT
        |--------------------------------------------------------------------------
        */

        if ($purchaseUnit === 'sack') {

            $quantityDisplay =
                number_format(
                    $quantitySacks,
                    0
                )
                .
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
                )
                . ' kg';


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
                ?? (
                    $subtotal
                    +
                    $shippingFee
                )
            );


        /*
        |--------------------------------------------------------------------------
        | BUYER
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


        /*
        |--------------------------------------------------------------------------
        | FARMER
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | RETURN DATA
        |--------------------------------------------------------------------------
        */

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
                $createdAt->format(
                    'F d, Y'
                ),


            'time' =>
                $createdAt->format(
                    'h:i A'
                ),


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