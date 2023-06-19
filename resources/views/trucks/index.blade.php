@extends("layouts.app")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Trucks</h3>
                <a href="{{ route("trucks.create") }}" class="btn btn-info float-end mb-2">Add truck</a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Unit</th>
                            <th>Year</th>
                            <th>Notes</th>
                            <th>Actions</th>
                            <th>Subunit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trucks as $truck)
                            <tr>
                                <td>{{ $truck->unit }}</td>
                                <td>{{ $truck->year }}</td>
                                <td>{{ $truck->notes }}</td>
                                <td><a href="{{ route("trucks.edit", $truck->id) }}" class="btn btn-outline-warning">Edit</a>
                                    <form method="post" action="{{ route("trucks.destroy", $truck->id)}}">
                                        @csrf
                                        @method("DELETE")
                                        <button class="btn btn-outline-danger mt-2">Delete</button>
                                    </form>
                                    <a href="{{ route("trucks.subunit", $truck->id) }}" class="btn btn-outline-success mt-2">Appoint a replacement</a>

                                </td>
                                <td>
                                    @foreach($truck->subunits as $sub)
                                        Truck: {{ $sub->unit }} <br>
                                        From: {{ $sub->pivot->start_date }}<br>
                                        Until {{ $sub->pivot->end_date }}<br>
                                        <form class="mb-1" method="post" action="{{ route("trucks.destroySub", ['truck' => $truck->id, 'truckSubunit' => $sub->id]) }}">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" class="btn btn-outline-danger mt-2">Cancel replacement</button>
                                        </form>

                                    @endforeach

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
