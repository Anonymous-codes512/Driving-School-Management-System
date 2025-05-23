<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th {
            background-color: #3b82f6;
            color: white;
            padding: 6px;
            text-align: left;
        }
        td {
            padding: 6px;
            vertical-align: middle;
        }
        img.logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        h2 {
            text-align: center;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <h2>Schools List</h2>
    <table>
        <thead>
            <tr>
                <th>Logo</th>
                <th>Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($schools as $school)
                <tr>
                    <td>
                        @if ($school->logo_path && Storage::disk('public')->exists($school->logo_path))
                            @php
                                $path = Storage::disk('public')->path($school->logo_path);
                                $type = pathinfo($path, PATHINFO_EXTENSION);
                                $data = base64_encode(file_get_contents($path));
                                $src = 'data:image/' . $type . ';base64,' . $data;
                            @endphp
                            <img src="{{ $src }}" class="logo" alt="Logo">
                        @else
                            No Logo
                        @endif
                    </td>
                    <td>{{ $school->name }}</td>
                    <td>{{ $school->address }}</td>
                    <td>{{ $school->phone }}</td>
                    <td>{{ ucfirst($school->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
