<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        PDF Ready | ANI-CARE
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        body {

            margin: 0;

            min-height: 100vh;

            background: #f3f6f5;

            font-family:
                "Segoe UI",
                sans-serif;

            color: #20252b;
        }

        .page {

            width: 100%;

            max-width: 520px;

            margin: 0 auto;

            padding: 30px 16px 60px;
        }

        .card-box {

            background: #fff;

            border: 1px solid #e5ebe8;

            border-radius: 22px;

            padding: 28px 22px;

            text-align: center;

            box-shadow:
                0 8px 30px
                rgba(0,0,0,.07);
        }

        .icon {

            width: 78px;

            height: 78px;

            margin:
                0 auto
                20px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            background: #e7f6ed;

            color: #198754;

            font-size: 36px;
        }

        h1 {

            color: #198754;

            font-size: 27px;

            font-weight: 800;

            margin-bottom: 8px;
        }

        .subtitle {

            color: #6c757d;

            font-size: 14px;

            margin-bottom: 25px;
        }

        .invoice-info {

            background: #f6f8f7;

            border-radius: 14px;

            padding: 16px;

            text-align: left;

            margin-bottom: 22px;
        }

        .info-label {

            color: #6c757d;

            font-size: 11px;

            margin-bottom: 2px;
        }

        .info-value {

            font-weight: 700;

            overflow-wrap: anywhere;
        }

        .button-stack {

            display: grid;

            grid-template-columns: 1fr;

            gap: 10px;
        }

        .btn {

            min-height: 50px;

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

            border-radius: 11px;

            font-weight: 700;
        }

        .help {

            margin-top: 20px;

            padding: 12px;

            background: #fff8e1;

            border: 1px solid #ffe7a3;

            border-radius: 11px;

            color: #6a5a19;

            font-size: 12px;

            line-height: 1.5;

            text-align: left;
        }

        @media(max-width:390px) {

            .page {

                padding-left: 12px;

                padding-right: 12px;
            }

            .card-box {

                padding:
                    24px
                    16px;
            }

            h1 {

                font-size: 24px;
            }
        }

    </style>

</head>


<body>

<div class="page">

    <div class="card-box">

        <div class="icon">

            <i class="bi bi-file-earmark-pdf-fill"></i>

        </div>


        <h1>
            PDF Ready
        </h1>


        <div class="subtitle">

            Your ANI-CARE invoice was generated successfully.

        </div>


        <div class="invoice-info">

            <div class="mb-3">

                <div class="info-label">
                    Invoice Number
                </div>

                <div class="info-value">
                    {{ $invoice['invoice_number'] }}
                </div>

            </div>


            <div>

                <div class="info-label">
                    File Name
                </div>

                <div class="info-value">
                    {{ $filename }}
                </div>

            </div>

        </div>


        <div class="button-stack">


            {{-- OPEN PDF --}}

            <a
                href="{{ $pdfUrl }}"
                class="btn btn-success"
            >
                <i class="bi bi-eye-fill"></i>

                Open PDF
            </a>


            {{-- DOWNLOAD PDF --}}

            <a
                href="{{ $pdfUrl }}"
                download="{{ $invoice['invoice_number'] }}.pdf"
                class="btn btn-outline-success"
            >
                <i class="bi bi-download"></i>

                Download PDF
            </a>


            {{-- BACK TO INVOICE --}}

            <a
                href="{{ route(
                    'resident.orders.invoice.show',
                    $invoice['order_id']
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left"></i>

                Back to Invoice
            </a>

        </div>


        <div class="help">

            <strong>
                <i class="bi bi-phone"></i>
                For Android:
            </strong>

            Tap <strong>Open PDF</strong> first.

            When the PDF opens, use your browser's download
            button or the three-dot menu to save the file.

        </div>

    </div>

</div>

</body>

</html>