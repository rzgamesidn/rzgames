<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $order->order_id }}</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #3b82f6; padding-bottom: 20px; }
        .info { margin: 20px 0; width: 100%; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #3b82f6; color: white; padding: 12px 10px; text-align: left; text-transform: uppercase; font-size: 12px; }
        td { padding: 12px 10px; border-bottom: 1px solid #eee; font-size: 13px; }
        .total { font-weight: bold; text-align: right; font-size: 18px; margin-top: 30px; color: #3b82f6; }
        .footer { margin-top: 50px; text-align: center; font-size: 11px; color: #999; border-top: 1px solid #eee; padding-top: 20px; }
        .status-badge { font-weight: bold; color: #059669; } /* Hijau untuk sukses */
    </style>
</head>
<body>
    <div class="header">
        <h1 style="color: #3b82f6; margin: 0; font-style: italic;">RZGAMES</h1>
        <p style="margin: 5px 0;">Terima kasih atas pembelian Anda, Bang!</p>
    </div>

    <table class="info">
        <tr>
            <td><strong>Order ID:</strong> #{{ $order->order_id }}</td>
            <td style="text-align: right;"><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y') }}</td>
        </tr>
        <tr>
            <td><strong>Email Pembeli:</strong> {{ $order->customer_email }}</td>
            <td style="text-align: right;">
                <strong>Status:</strong> 
                <span class="status-badge">
                    {{-- LOGIKA SMART LABEL: PROCESSED/SUCCESS JADI PAID --}}
                    {{ in_array($order->status, ['success', 'processed', 'paid']) ? 'PAID / SUCCESS' : strtoupper($order->status) }}
                </span>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Produk Game</th>
                <th style="text-align: right;">Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product->title }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total Bayar: Rp {{ number_format($order->total_price, 0, ',', '.') }}
    </div>

    <div class="footer">
        <p><strong>PENTING:</strong> Ini adalah bukti pembayaran sah dari RZGAMES.</p>
        <p>Silakan kunjungi menu <strong>"Library Game Kamu"</strong> di website untuk mengambil link download Google Drive Anda.</p>
        <p style="margin-top: 10px;">&copy; {{ date('Y') }} RZGAMES - Gaming Tanpa Batas.</p>
    </div>
</body>
</html>