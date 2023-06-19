@extends("layouts.app")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">
                Edit
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
                <form method="post" action="{{ route("trucks.update", $truck->id) }}">
                    @csrf
                    @method("put")
                    <label>Unit</label>
                    <input class="form-control" name="unit" type="text" value="{{ $truck->unit }}">
                    <label>Year</label>
                    <input class="form-control" name="year" type="text" value="{{ $truck->year }}">
                    <label>Notes</label>
                    <input class="form-control" name="notes" type="text" value="{{ $truck->notes }}">
                    <button class="btn btn-success mt-2" type="submit">Update</button>
                    <a href="{{ route("trucks.index") }}" class="btn btn-info float-end mt-2">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection
