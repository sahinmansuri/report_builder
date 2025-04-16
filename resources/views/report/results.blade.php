<div class="table-responsive"> <!-- Makes the table scrollable on smaller screens -->
    <table class="table table-bordered table-hover table-striped"> <!-- Adds Bootstrap table styles -->
        <thead class="thead-dark"> <!-- Optional: Dark header styling -->
            <tr>
                {{-- <th scope="col">No</th> --}}
                @foreach (($data['header']??[]) as $header => $subHeader)
                    <th scope="col">{{ $header ?? null }}</th>
                @endforeach
            </tr>
        </thead>
        @if(($data['body']??[]))
        <tbody>
            @foreach (($data['body']??[]) as $count => $values)
                <tr>
                    {{-- <td>{{ ++$count }}</td> --}}
                    @foreach ($values as $field => $value)
                        @if (is_array($value))
                            <td>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered"> <!-- Nested table -->
                                        <thead class="thead-light">
                                            @if (isset($data['header'][$field]) && is_array($data['header'][$field]))
                                                <tr>
                                                    @foreach ($data['header'][$field] as $subHeader)
                                                        <th scope="col">{{ $subHeader ?? null }}</th>
                                                    @endforeach
                                                </tr>
                                            @endif
                                        </thead>
                                        <tbody>
                                            @if (!array_key_exists('file_name', $value))
                                                @foreach ($value as $subValue)
                                                    <tr>
                                                        @if (is_array($subValue))
                                                            @foreach ($subValue as $innerSubValue)
                                                                <td>{{ $innerSubValue ?? null }}</td>
                                                            @endforeach
                                                        @else
                                                            <td>{{ $subValue ?? null }}</td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            @else
                                                @if (file_exists(public_path($value['file_path'])))
                                                    <td>
                                                        <a href="{{ asset($value['file_path']) }}" download
                                                           class="btn btn-primary">{{ $value['file_name'] ?? 'Download' }}</a>
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        @else
                            <td>{!! $value ?? '' !!}</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        @else
        <tbody>
            <tr>
                <td class="text-center" colspan="{!! count($data['header']??[]) !!}">No record found</td>
            </tr>
        </tbody>
        @endif
    </table>
</div>
