<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contract Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #CC0000, #003366);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .section {
            background: #f8f9fa;
            border-left: 4px solid #CC0000;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 0 8px 8px 0;
        }

        .section.budget {
            border-left-color: #003366;
        }

        .section h2 {
            margin-top: 0;
            color: #CC0000;
        }

        .section.budget h2 {
            color: #003366;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #003366;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        .section.duration {
            border-left-color: #FF8C00;
        }

        .section.duration h2 {
            color: #FF8C00;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            color: #666;
            font-size: 14px;
        }

        .warning {
            color: #CC0000;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>📋 Contract Management System - Reminder</h1>
    </div>

    @if($greeting)
        <div style="margin-bottom: 20px; white-space: pre-line;">{{ $greeting }}</div>
    @endif

    @if($expiringContracts->count() > 0)
        <div class="section">
            <h2>⏰ Contracts Expiring Soon (within {{ $reminderMonths }} months)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Fungsi</th>
                        <th>Contract Number</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Vendor</th>
                        <th>End Date</th>
                        <th>Days Left</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expiringContracts as $contract)
                        <tr>
                            <td>{{ $contract->field_label }}</td>
                            <td>{{ $contract->fungsi_label }}</td>
                            <td>{{ $contract->contract_number }}</td>
                            <td>{{ $contract->title }}</td>
                            <td>{{ $contract->current_status }}@if($contract->amendments->where('is_bridging', true)->count())
                            <span style="color: #FF8C00; font-weight: bold;">(Bridging)</span>@endif</td>
                            <td>{{ $contract->vendor_name }}</td>
                            <td>{{ $contract->end_date->format('d M Y') }}</td>
                            <td class="warning">{{ round(now()->diffInDays($contract->end_date)) }} days</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($durationExpiringContracts->count() > 0)
        <div class="section duration">
            <h2>📅 Contract Duration Expiring Soon (within {{ $reminderMonths }} months)</h2>
            <p style="font-size: 14px; color: #666; margin-bottom: 10px;">
                Contracts with amended end dates approaching expiration.
            </p>
            <table>
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Fungsi</th>
                        <th>Contract Number</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Effective End Date</th>
                        <th>Days Left</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($durationExpiringContracts as $contract)
                        <tr>
                            <td>{{ $contract->field_label }}</td>
                            <td>{{ $contract->fungsi_label }}</td>
                            <td>{{ $contract->contract_number }}</td>
                            <td>{{ $contract->title }}</td>
                            <td>{{ $contract->current_status }}@if($contract->amendments->where('is_bridging', true)->count()) <span style="color: #FF8C00; font-weight: bold;">(Bridging)</span>@endif</td>
                            <td>{{ \Carbon\Carbon::parse($contract->effective_end_date)->format('d M Y') }}</td>
                            <td class="warning">
                                {{ round(now()->diffInDays(\Carbon\Carbon::parse($contract->effective_end_date))) }} days
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($lowBudgetContracts->count() > 0)
        <div class="section budget">
            <h2>💰 Contracts with Low Budget (≤ {{ $budgetWarningPercent }}% remaining)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Fungsi</th>
                        <th>Contract Number</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Total Value</th>
                        <th>Remaining</th>
                        <th>Remaining %</th>
                        <th>Est. Depletion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowBudgetContracts as $contract)
                        <tr>
                            <td>{{ $contract->field_label }}</td>
                            <td>{{ $contract->fungsi_label }}</td>
                            <td>{{ $contract->contract_number }}</td>
                            <td>{{ $contract->title }}</td>
                            <td>{{ $contract->current_status }}@if($contract->amendments->where('is_bridging', true)->count())
                            <span style="color: #FF8C00; font-weight: bold;">(Bridging)</span>@endif</td>
                            <td>Rp {{ number_format($contract->total_value, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($contract->remaining_value, 0, ',', '.') }}</td>
                            <td class="warning">{{ $contract->remaining_percent }}%</td>
                            <td>{{ $contract->estimated_depletion_month ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="footer">
        <div style="white-space: pre-line;">{{ $footerMessage }}</div>
        <p>Generated at: {{ now()->format('d M Y H:i:s') }}</p>
    </div>
</body>

</html>