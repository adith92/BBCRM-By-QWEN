<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            font-size: 11px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #003887;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 20px;
            font-weight: bold;
            color: #003887;
            margin: 0;
        }
        .subtitle {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #666666;
            margin: 2px 0 0 0;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #222222;
            margin: 15px 0 5px 0;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .meta-label {
            font-weight: bold;
            color: #555555;
            width: 120px;
        }
        .kpi-container {
            margin-bottom: 25px;
            overflow: hidden;
            display: block;
        }
        .kpi-box {
            width: 30%;
            float: left;
            background-color: #f8f9ff;
            border: 1px solid #d3e4fe;
            border-radius: 6px;
            padding: 10px;
            margin-right: 3%;
        }
        .kpi-box:last-child {
            margin-right: 0;
        }
        .kpi-val {
            font-size: 16px;
            font-weight: bold;
            color: #003887;
            margin-top: 5px;
        }
        .kpi-lbl {
            font-size: 8px;
            text-transform: uppercase;
            font-weight: bold;
            color: #777777;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #003887;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
            margin: 25px 0 10px 0;
            clear: both;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
            text-align: left;
            padding: 6px 8px;
            border-bottom: 1px solid #cbd5e1;
            font-size: 9px;
            text-transform: uppercase;
        }
        .data-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>

    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <h1 class="logo">GOLDEN BIRD</h1>
                    <p class="subtitle">B2B Fleet Management Portal</p>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <span style="background-color: #ef4444; color: white; padding: 2px 6px; font-size: 8px; font-weight: bold; border-radius: 3px; text-transform: uppercase;">Demo Mode</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="report-title">{{ $title }}</div>

    <table class="meta-table">
        <tr>
            <td class="meta-label">Export Date:</td>
            <td>{{ $generated_at }}</td>
            <td class="meta-label">System Environment:</td>
            <td>Staging / Demo MVP v1.0.0</td>
        </tr>
        <tr>
            <td class="meta-label">Scope:</td>
            <td>Full Organization Data</td>
            <td class="meta-label">Security Tier:</td>
            <td>Internal Confidential</td>
        </tr>
    </table>

    <div class="section-title">Consolidated Performance Indicators</div>
    
    <div class="kpi-container">
        @if($type === 'Fleet')
            <div class="kpi-box">
                <span class="kpi-lbl">Total Fleet</span>
                <div class="kpi-val">{{ $total }} units</div>
            </div>
            <div class="kpi-box">
                <span class="kpi-lbl">Active & Ready</span>
                <div class="kpi-val">{{ $available }} units</div>
            </div>
            <div class="kpi-box">
                <span class="kpi-lbl">In Maintenance</span>
                <div class="kpi-val">{{ $maintenance }} units</div>
            </div>
        @elseif($type === 'Sales')
            <div class="kpi-box">
                <span class="kpi-lbl">Total Clients</span>
                <div class="kpi-val">{{ $total_clients }} corporate</div>
            </div>
            <div class="kpi-box">
                <span class="kpi-lbl">Total Booking Logs</span>
                <div class="kpi-val">{{ $total_bookings }} placements</div>
            </div>
            <div class="kpi-box">
                <span class="kpi-lbl">Pipeline Status</span>
                <div class="kpi-val">Optimized</div>
            </div>
        @else
            <div class="kpi-box">
                <span class="kpi-lbl">Total Invoiced</span>
                <div class="kpi-val">Rp {{ number_format($total_invoiced, 0) }}</div>
            </div>
            <div class="kpi-box">
                <span class="kpi-lbl">Total Collected</span>
                <div class="kpi-val">Rp {{ number_format($total_payments, 0) }}</div>
            </div>
            <div class="kpi-box">
                <span class="kpi-lbl">Remaining Balance</span>
                <div class="kpi-val" style="color: #ba1a1a;">Rp {{ number_format($total_outstanding, 0) }}</div>
            </div>
        @endif
    </div>

    <div class="clear"></div>

    <div class="section-title">Detailed Ledger Breakdown</div>

    @if($type === 'Fleet')
        <table class="data-table">
            <thead>
                <tr>
                    <th>Vehicle Brand</th>
                    <th>Model Variant</th>
                    <th>License Plate</th>
                    <th>Operational Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicles as $v)
                    <tr>
                        <td><strong>{{ $v->brand }}</strong></td>
                        <td>{{ $v->model ?? '-' }}</td>
                        <td><code>{{ $v->plate }}</code></td>
                        <td>{{ strtoupper($v->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($type === 'Sales')
        <table class="data-table">
            <thead>
                <tr>
                    <th>PIC Name</th>
                    <th>Company Corporate</th>
                    <th>Email Contact</th>
                    <th>Phone Contact</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $c)
                    <tr>
                        <td><strong>{{ $c->name }}</strong></td>
                        <td>{{ $c->company ?? 'Personal' }}</td>
                        <td>{{ $c->email }}</td>
                        <td>{{ $c->phone }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th>Corporate Client</th>
                    <th>Grand Total</th>
                    <th>Unpaid Balance</th>
                    <th>Ledger Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $i)
                    <tr>
                        <td>#INV-{{ str_pad($i->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td><strong>{{ $i->booking->client->name }}</strong></td>
                        <td>Rp {{ number_format($i->total_amount, 2) }}</td>
                        <td style="color: #ba1a1a;">Rp {{ number_format($i->remaining_balance, 2) }}</td>
                        <td>{{ strtoupper($i->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        © 2026 Golden Bird B2B Fleet Management System. Certified Digital PDF Report. Confidential document.
    </div>

</body>
</html>
