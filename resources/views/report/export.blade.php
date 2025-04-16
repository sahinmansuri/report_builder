<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export</title>
</head>
<body>
    <div class="table-responsive"> <!-- Makes the table scrollable on smaller screens -->
        <table class="table table-bordered table-hover table-striped"> <!-- Adds Bootstrap table styles -->
            <thead class="thead-dark">
                <!-- First Row: Main Headers -->
                <tr>
                    @foreach (($data['header'] ?? []) as $header => $subHeader)
                        @if (is_array($subHeader))
                            <!-- If there are sub-headers, create a main header cell with colspan -->
                            <th scope="col" colspan="{{ count($subHeader) }}" style="text-align:center">
                                {{ $header ?? null }}
                            </th>
                        @else
                            <!-- If there's no sub-header, just create a single header cell -->
                            <th scope="col" style="text-align:center">{{ $header ?? null }}</th>
                        @endif
                    @endforeach
                </tr>

                <!-- Second Row: Sub-Headers -->
                <tr>
                    @foreach (($data['header'] ?? []) as $header => $subHeader)
                        @if (is_array($subHeader))
                            @foreach ($subHeader as $sub)
                                <th scope="col" style="text-align:center">{{ $sub ?? null }}</th>
                            @endforeach
                        @else
                            <!-- If there's no sub-header, create an empty cell -->
                            <th scope="col" style="text-align:center"></th>
                        @endif
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @if (!empty($data['body'] ?? []))
                    @foreach ($data['body'] as $user)
                        @for ($row = 0; $row < ($user['max_rows'] ?? 1); $row++)
                            <tr>
                                @foreach (($data['header'] ?? []) as $header => $subHeader)
                                    @if (is_array($user[$header] ?? null))
                                        @if (isset($user[$header][$row]))
                                            @if (is_array($user[$header][$row]))
                                                @foreach ($user[$header][$row] as $value)
                                                    <td>{{ $value ?? '' }}</td>
                                                @endforeach
                                            @else
                                                <td>{{ $user[$header][$row] ?? '' }}</td>
                                            @endif
                                        @else
                                            <td colspan="{{ is_array($subHeader) ? count($subHeader) : 1 }}"></td>
                                        @endif
                                    @else
                                        @if ($row === 0)
                                            <td rowspan="{{ $user['max_rows'] ?? 1 }}">{{ $user[$header] ?? '' }}</td>
                                        @endif
                                    @endif
                                @endforeach
                            </tr>
                        @endfor
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ count($data['header'] ?? []) }}" class="text-center">No records found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</body>
</html>

