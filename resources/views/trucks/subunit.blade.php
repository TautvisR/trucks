@extends("layouts.app")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">
                Appoint a replacement for : {{ $truck->unit }}
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card-body">
                <form method="post" action="{{ route("trucks.substitute", $truck->id) }}">
                    @csrf
                    <select class="form-select" name="subunit_truck_id">
                        @foreach($trucks as $truck)
                            <option value="{{ $truck->id }}"> {{ $truck->unit }}</option>
                        @endforeach
                    </select>
                    <label>Start date</label>
                    <input class="form-control" type="date" name="start_date">
                    <label>End date</label>
                    <input class="form-control" type="date" name="end_date">
                    <button class="btn btn-dark mt-2">Substitute</button>
                    <a href="{{ route("trucks.index") }}" class="btn btn-info float-end mt-2">Back</a>
                </form>
            </div>
        </div>
        <div class="card mt-2">
        <div class="card-header">
            Existing replacements
        </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Subunit</th>
                            <th>Start date</th>
                            <th>End date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subunits as $sub)
                            <tr>
                                <td>{{ $sub->unit }}</td>
                                <td>{{ $sub->pivot->start_date }}</td>
                                <td>{{ $sub->pivot->end_date }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
