<!doctype html>
<html lang="en" class="layout-wide" dir="ltr" data-skin="default" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Invoice #{{ $invoice->invoice_number }} - Print</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #697a8d;
            background: #fff;
        }
        
        .invoice-print {
            padding: 2rem;
            max-width: 900px;
            margin: 0 auto;
        }
        
        .p-6 {
            padding: 1.5rem;
        }
        
        .d-flex {
            display: flex;
        }
        
        .align-items-center {
            align-items: center;
        }
        
        .justify-content-between {
            justify-content: space-between;
        }
        
        .flex-row {
            flex-direction: row;
        }
        
        .mb-6 {
            margin-bottom: 1.5rem;
        }
        
        .mb-1 {
            margin-bottom: 0.25rem;
        }
        
        .mb-0 {
            margin-bottom: 0;
        }
        
        .mb-2 {
            margin-bottom: 0.5rem;
        }
        
        .mt-0 {
            margin-top: 0;
        }
        
        .me-2 {
            margin-right: 0.5rem;
        }
        
        .pe-4 {
            padding-right: 1rem;
        }
        
        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        
        .px-0 {
            padding-left: 0;
            padding-right: 0;
        }
        
        .py-6 {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
        
        .py-12 {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }
        
        .ps-0 {
            padding-left: 0;
        }
        
        .align-top {
            vertical-align: top;
        }
        
        .text-end {
            text-align: right;
        }
        
        .text-nowrap {
            white-space: nowrap;
        }
        
        .w-px-100 {
            width: 100px;
        }
        
        .fw-medium {
            font-weight: 500;
        }
        
        .fw-semibold {
            font-weight: 600;
        }
        
        .h4 {
            font-size: 1.5rem;
            font-weight: 500;
            line-height: 1.2;
        }
        
        .h6 {
            font-size: 1rem;
            font-weight: 500;
            line-height: 1.2;
        }
        
        .svg-illustration {
            display: flex;
            align-items: center;
        }
        
        .gap-2 {
            gap: 0.5rem;
        }
        
        .gap-3 {
            gap: 0.75rem;
        }
        
        .app-brand-logo {
            display: inline-flex;
        }
        
        .text-primary {
            color: #696cff;
        }
        
        .app-brand-text {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .border {
            border: 1px solid #d9dee3;
        }
        
        .border-bottom {
            border-bottom: 1px solid #d9dee3;
        }
        
        .border-bottom-0 {
            border-bottom: 0;
        }
        
        .rounded {
            border-radius: 0.375rem;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse;
        }
        
        .table thead {
            background-color: #f5f5f9;
        }
        
        .table th {
            padding: 0.75rem;
            text-align: left;
            font-weight: 500;
            color: #566a7f;
            border-bottom: 1px solid #d9dee3;
        }
        
        .table td {
            padding: 0.75rem;
            border-bottom: 1px solid #d9dee3;
        }
        
        .table tbody tr:last-child td {
            border-bottom: 0;
        }
        
        .table-borderless td,
        .table-borderless th {
            border: 0;
        }
        
        .table-light {
            background-color: #f5f5f9;
        }
        
        .text-heading {
            color: #566a7f;
        }
        
        hr {
            margin: 1.5rem 0;
            border: 0;
            border-top: 1px solid #d9dee3;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-left: -0.75rem;
            margin-right: -0.75rem;
        }
        
        .col-12 {
            flex: 0 0 100%;
            max-width: 100%;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        @media print {
            body {
                background: #fff;
            }
            
            .invoice-print {
                padding: 0;
            }
            
            @page {
                margin: 0.5in;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-print p-6">
        <div class="d-flex justify-content-between flex-row">
            <div class="mb-6">
                @php
                    $org = \App\Models\OrganizationSetting::getSettings();
                @endphp
                <div class="d-flex svg-illustration align-items-center gap-2 mb-6">
                    <span class="app-brand-logo demo">
                        <span class="text-primary">
                            <svg width="30" height="24" viewBox="0 0 250 196" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.3002 1.25469L56.655 28.6432C59.0349 30.1128 60.4839 32.711 60.4839 35.5089V160.63C60.4839 163.468 58.9941 166.097 56.5603 167.553L12.2055 194.107C8.3836 196.395 3.43136 195.15 1.14435 191.327C0.395485 190.075 0 188.643 0 187.184V8.12039C0 3.66447 3.61061 0.0522461 8.06452 0.0522461C9.56056 0.0522461 11.0271 0.468577 12.3002 1.25469Z" fill="currentColor" />
                                <path opacity="0.077704" fill-rule="evenodd" clip-rule="evenodd" d="M0 65.2656L60.4839 99.9629V133.979L0 65.2656Z" fill="black" />
                                <path opacity="0.077704" fill-rule="evenodd" clip-rule="evenodd" d="M0 65.2656L60.4839 99.0795V119.859L0 65.2656Z" fill="black" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M237.71 1.22393L193.355 28.5207C190.97 29.9889 189.516 32.5905 189.516 35.3927V160.631C189.516 163.469 191.006 166.098 193.44 167.555L237.794 194.108C241.616 196.396 246.569 195.151 248.856 191.328C249.605 190.076 250 188.644 250 187.185V8.09597C250 3.64006 246.389 0.027832 241.935 0.027832C240.444 0.027832 238.981 0.441882 237.71 1.22393Z" fill="currentColor" />
                                <path opacity="0.077704" fill-rule="evenodd" clip-rule="evenodd" d="M250 65.2656L189.516 99.8897V135.006L250 65.2656Z" fill="black" />
                                <path opacity="0.077704" fill-rule="evenodd" clip-rule="evenodd" d="M250 65.2656L189.516 99.0497V120.886L250 65.2656Z" fill="black" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.2787 1.18923L125 70.3075V136.87L0 65.2465V8.06814C0 3.61223 3.61061 0 8.06452 0C9.552 0 11.0105 0.411583 12.2787 1.18923Z" fill="currentColor" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.2787 1.18923L125 70.3075V136.87L0 65.2465V8.06814C0 3.61223 3.61061 0 8.06452 0C9.552 0 11.0105 0.411583 12.2787 1.18923Z" fill="white" fill-opacity="0.15" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M237.721 1.18923L125 70.3075V136.87L250 65.2465V8.06814C250 3.61223 246.389 0 241.935 0C240.448 0 238.99 0.411583 237.721 1.18923Z" fill="currentColor" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M237.721 1.18923L125 70.3075V136.87L250 65.2465V8.06814C250 3.61223 246.389 0 241.935 0C240.448 0 238.99 0.411583 237.721 1.18923Z" fill="white" fill-opacity="0.3" />
                            </svg>
                        </span>
                    </span>
                    <span class="h4 mb-0 app-brand-text fw-semibold">{{ $org->organization_name }}</span>
                </div>
                <p class="mb-1">{{ $org->address ?? '' }}</p>
                @if($org->city || $org->state || $org->country)
                <p class="mb-1">{{ trim(implode(', ', array_filter([$org->city, $org->state, $org->country]))) }}</p>
                @endif
                @if($org->postal_code)
                <p class="mb-1">{{ $org->postal_code }}</p>
                @endif
                <p class="mb-0">
                    @if($org->phone){{ $org->phone }}@endif
                    @if($org->phone && $org->email), @endif
                    @if($org->email){{ $org->email }}@endif
                </p>
            </div>
            <div>
                <h4 class="mb-6">INVOICE #{{ $invoice->invoice_number }}</h4>
                <div class="mb-1">
                    <span>Date Issues:</span>
                    <span>{{ $invoice->invoice_date ? $invoice->invoice_date->format('F d, Y') : 'N/A' }}</span>
                </div>
                @if($invoice->due_date)
                <div>
                    <span>Date Due:</span>
                    <span>{{ $invoice->due_date->format('F d, Y') }}</span>
                </div>
                @endif
            </div>
        </div>

        <hr class="mb-6" />

        <div class="d-flex justify-content-between mb-6">
            <div class="my-2">
                <h6>Invoice To:</h6>
                <p class="mb-1">{{ $invoice->customer_name }}</p>
                @if($invoice->customer_address)
                <p class="mb-1">{{ $invoice->customer_address }}</p>
                @endif
                @if($invoice->customer_phone)
                <p class="mb-1">{{ $invoice->customer_phone }}</p>
                @endif
                @if($invoice->customer_email)
                <p class="mb-0">{{ $invoice->customer_email }}</p>
                @endif
            </div>
            <div class="my-2">
                <h6>Bill To:</h6>
                <table>
                    <tbody>
                        <tr>
                            <td class="pe-4">Total Due:</td>
                            <td><strong>{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->total_amount ?? 0, 2) }}</strong></td>
                        </tr>
                        @if($org->bank_name)
                        <tr>
                            <td class="pe-4">Bank name:</td>
                            <td>{{ $org->bank_name }}</td>
                        </tr>
                        @endif
                        @if($org->bank_country)
                        <tr>
                            <td class="pe-4">Country:</td>
                            <td>{{ $org->bank_country }}</td>
                        </tr>
                        @endif
                        @if($org->iban)
                        <tr>
                            <td class="pe-4">IBAN:</td>
                            <td>{{ $org->iban }}</td>
                        </tr>
                        @endif
                        @if($org->swift_code)
                        <tr>
                            <td class="pe-4">SWIFT code:</td>
                            <td>{{ $org->swift_code }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="table-responsive border border-bottom-0 rounded">
            <table class="table m-0">
                <thead class="table-light">
                    <tr>
                        <th>Item</th>
                        <th>Description</th>
                        <th>Cost</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @if($invoice->booking && $invoice->booking->tour)
                    <tr>
                        <td class="text-nowrap text-heading">{{ $invoice->booking->tour->name }}</td>
                        <td class="text-nowrap">Tour Package</td>
                        <td>{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->subtotal ?? 0, 2) }}</td>
                        <td>1</td>
                        <td>{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->subtotal ?? 0, 2) }}</td>
                    </tr>
                    @else
                    <tr>
                        <td class="text-nowrap text-heading">Service</td>
                        <td class="text-nowrap">Tour & Travel Services</td>
                        <td>{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->subtotal ?? 0, 2) }}</td>
                        <td>1</td>
                        <td>{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->subtotal ?? 0, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="table-responsive">
            <table class="table m-0 table-borderless">
                <tbody>
                    <tr>
                        <td class="align-top px-6 py-6">
                            <p class="mb-1">
                                <span class="me-2 fw-medium">Salesperson:</span>
                                <span>{{ $org->organization_name }}</span>
                            </p>
                            <span>{{ $invoice->notes ?? 'Thanks for your business' }}</span>
                        </td>
                        <td class="px-0 py-12 w-px-100">
                            <p class="mb-2">Subtotal:</p>
                            @if(($invoice->discount_amount ?? 0) > 0)
                            <p class="mb-2">Discount:</p>
                            @endif
                            @if(($invoice->tax_amount ?? 0) > 0)
                            <p class="mb-2 border-bottom pb-2">Tax:</p>
                            @endif
                            <p class="mb-0 pt-2">Total:</p>
                        </td>
                        <td class="text-end px-0 py-6 w-px-100">
                            <p class="fw-medium mb-2">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->subtotal ?? 0, 2) }}</p>
                            @if(($invoice->discount_amount ?? 0) > 0)
                            <p class="fw-medium mb-2">-{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->discount_amount, 2) }}</p>
                            @endif
                            @if(($invoice->tax_amount ?? 0) > 0)
                            <p class="fw-medium mb-2 border-bottom pb-2">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->tax_amount, 2) }}</p>
                            @endif
                            <p class="fw-medium mb-0 pt-2">{{ $invoice->currency ?? 'USD' }} {{ number_format($invoice->total_amount ?? 0, 2) }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr class="mt-0 mb-6" />
        <div class="row">
            <div class="col-12">
                <span class="fw-medium">Note:</span>
                <span>{{ $invoice->notes ?? 'It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance projects. Thank You!' }}</span>
            </div>
        </div>
    </div>
    
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>

