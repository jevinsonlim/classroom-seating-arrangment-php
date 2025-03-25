<!DOCTYPE html>
<html>
<head>
    <title>Seat Plan</title>
    <style>
        table {
            border-collapse: collapse;
            width: auto;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <h1>Seat Plan</h1>
    <p>Section: {{ $sectionName }}</p>
    <p>Subject: {{ $subjectName }}</p>

    <table>
        <tr>
            <th></th>
            @for ($col = 1; $col <= $maxColumn; $col++)
                <th>{{ $col }}</th>
            @endfor
        </tr>
        @for ($row = 1; $row <= $maxRow; $row++)
            <tr>
                <th>{{ $row }}</th>
                @for ($col = 1; $col <= $maxColumn; $col++)
                    <td>
                        @php
                            $studentName = '';
                            $seat = $seats->where('row', $row)->where('column', $col)->first();
                            if ($seat && $seat->student) {
                                $studentName = htmlspecialchars($seat->student); 
                            }
                            echo $studentName;
                        @endphp
                    </td>
                @endfor
            </tr>
        @endfor
    </table>
</body>
</html>