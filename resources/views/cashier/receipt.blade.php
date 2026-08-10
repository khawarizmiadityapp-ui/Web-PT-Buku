<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt - {{ $transaction->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            max-width: 80mm;
            margin: 0 auto;
            padding: 10mm;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px dashed #000;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .company-info {
            font-size: 10px;
            color: #333;
        }
        .receipt-info {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }
        .receipt-info div {
            margin-bottom: 3px;
        }
        .items-table {
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
        }
        .items-table tr {
            margin-bottom: 5px;
        }
        .items-table td {
            padding: 3px 0;
        }
        .item-name {
            font-weight: bold;
        }
        .item-detail {
            display: flex;
            justify-content: space-between;
        }
        .totals {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px dashed #000;
        }
        .totals div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        .total-line {
            font-weight: bold;
            font-size: 14px;
            margin-top: 5px;
            padding-top: 5px;
            border-top: 1px solid #000;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 15px;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            background: #000;
            color: #fff;
            font-weight: bold;
            margin: 10px 0;
        }
        @media print {
            body {
                padding: 0;
            }
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-header">
        <div class="company-name">PT NUSANTARA</div>
        <div class="company-info">
            Distribution Solutions<br>
            Jl. Sudirman No. 123, Jakarta<br>
            Tel: (021) 123-4567<br>
            www.ptnusantara.com
        </div>
    </div>

    <div class="receipt-info">
        <div><strong>INVOICE:</strong> {{ $transaction->invoice_number }}</div>
        <div><strong>DATE:</strong> {{ $transaction->created_at->format('d/m/Y H:i') }}</div>
        <div><strong>CUSTOMER:</strong> {{ $transaction->customer_name }}</div>
        @if($transaction->payment_method)
        <div><strong>PAYMENT:</strong> {{ $transaction->payment_method }}</div>
        @endif
        <div style="text-align: center; margin-top: 8px;">
            <span class="status-badge">{{ strtoupper($transaction->payment_status) }}</span>
        </div>
    </div>

    <table class="items-table">
        @foreach($transaction->items as $item)
        <tr>
            <td colspan="3" class="item-name">{{ $item->product_name }}</td>
        </tr>
        <tr>
            <td width="15%">{{ $item->quantity }} x</td>
            <td width="45%" style="text-align: right;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
            <td width="40%" style="text-align: right; font-weight: bold;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr><td colspan="3" style="height: 5px;"></td></tr>
        @endforeach
    </table>

    <div class="totals">
        <div>
            <span>SUBTOTAL:</span>
            <span>Rp {{ number_format($transaction->total_amount + $transaction->discount_amount - $transaction->tax_amount, 0, ',', '.') }}</span>
        </div>
        
        @if($transaction->discount_amount > 0)
        <div>
            <span>DISCOUNT:</span>
            <span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
        </div>
        @endif
        
        @if($transaction->tax_amount > 0)
        <div>
            <span>TAX (11%):</span>
            <span>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
        </div>
        @endif
        
        <div class="total-line">
            <span>TOTAL:</span>
            <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
        </div>
        
        <div style="margin-top: 10px;">
            <span>PAID:</span>
            <span>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
        </div>
        
        @if($transaction->paid_amount > $transaction->total_amount)
        <div>
            <span>CHANGE:</span>
            <span>Rp {{ number_format($transaction->paid_amount - $transaction->total_amount, 0, ',', '.') }}</span>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Thank you for your purchase!</p>
        <p>Customer Service: cs@ptnusantara.com</p>
        <p style="margin-top: 10px;">{{ $transaction->created_at->format('d M Y H:i:s') }}</p>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
