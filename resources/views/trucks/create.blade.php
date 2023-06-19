@extends("layouts.app")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">
                Add new truck
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
                <form method="post" action="{{ route("trucks.store") }}">
                    @csrf
                    <label>Unit</label>
                    <input class="form-control" name="unit" type="text">
                    <label>Year</label>
                    <input class="form-control" name="year" type="text">
                    <label>Notes</label>
                    <input class="form-control" type="text" name="notes">
                    <button class="btn btn-success mt-2" type="submit">Add</button>
                    <a href="{{ route("trucks.index") }}" class="btn btn-info float-end mt-2">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection
