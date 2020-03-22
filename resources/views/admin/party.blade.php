@extends('layouts.admin')

@section('content')
<div class="container pt-4 pb-4">
    <h3>{{$party->name}}</h3>
    <h5>On {{$party->seats()}} seats overall</h5>
    <div class="row mt-2">
        <div class="col">
            <form method="POST" action="{{route('admin.party.updateseats', $party->code)}}" class=mb-3">
                @csrf
                <div class="form-group">
                    <label for="exampleForm2">List vote</label>
                    <input name="list_votes" type="number" value="{{$party->list_votes}}" id="exampleForm2" class="form-control">
                </div>
                <div class="form-group">
                    <label for="exampleForm2">List seats</label>
                    <input name="list_seats" type="number" value="{{$party->list_seats}}" id="exampleForm2" class="form-control">
                </div>
                <input type="submit" class="btn btn-primary" value="Update">
            </form>
        </div>
        <div class="col">
            <h5>Seats won</h5>
            <table id="declaredCont" class="table table-bordered table-hover">
                <thead>
                <th>Name</th>
                <th>Winner</th>
                <th>Votes</th>
                </thead>
                <tbody>
                @foreach ($party->constituencySeats() as $seat)
                    <tr>
                        <td><a href="#">{{$seat->name}}</a></td>
                        <td>{{$seat->winner()->name}}</td>
                        <td>{{$seat->winner()->constituency_votes}} (leads by {{$seat->winner()->constituency_votes - $seat->runnerUp()->constituency_votes}})</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <script>
                $('#declaredCont').DataTable();
            </script>
        </div>
    </div>
</div>
@endsection