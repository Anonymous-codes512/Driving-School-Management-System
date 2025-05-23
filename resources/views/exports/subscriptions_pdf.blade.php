<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Subscriptions List</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1em;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f0f0f0;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h2>Subscriptions List</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Subscription Name</th>
                <th>Price (PKR)</th>
                <th>Duration</th>
                <th>Features</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscriptions as $index => $subscription)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $subscription->name }}</td>
                    <td>{{ number_format($subscription->price, 2) }}</td>
                    <td>{{ $subscription->duration }}</td>
                    <td>
                        @if(is_array($subscription->features) || is_object($subscription->features))
                            {{ implode(', ', (array)$subscription->features) }}
                        @else
                            {{ $subscription->features }}
                        @endif
                    </td>
                    <td>{{ ucfirst($subscription->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
