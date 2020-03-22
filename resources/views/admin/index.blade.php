@extends('layouts.admin')

@section('content')
<div class="container pt-4 pb-4">
    <h3>Admin</h3>
    <div class="row">
        <div class="col">
            <h5>Declared Constituencies</h5>
            <table id="declaredCont" class="table table-bordered table-hover">
                <thead>
                <th>Name</th>
                <th>Declared At</th>
                </thead>
                <tbody>
                @foreach ($declaredCont as $c)
                    <tr>
                        <td><a href="{{route('admin.constituency', $c->code)}}">{{$c->name}}</a></td>
                        <td>{{$c->declared_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <script>
                $('#declaredCont').DataTable({"bSort":false});
            </script>
        </div>
        <div class="col">
            <h5>Undeclared Constituencies</h5>
            <table id="undeclaredCont" class="table table-bordered table-hover">
                <thead>
                <th>Name</th>
                </thead>
                <tbody>
                @foreach ($undeclaredCont as $c)
                    <tr>
                        <td><a href="{{route('admin.constituency', $c->code)}}">{{$c->name}}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <script>
                $('#undeclaredCont').DataTable({"bSort":false});
            </script>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <h5>Parties</h5>
            <table id="parties" class="table table-bordered table-hover">
                <thead>
                <th>Name</th>
                <th>Seats (List + Cont)</th>
                <th>List Votes</th>
                </thead>
                <tbody>
                @foreach ($parties as $c)
                    <tr>
                        <td><a href="{{route('admin.party', $c->code)}}">{{$c->name}}</a></td>
                        <td>{{$c->seats()}}</td>
                        <td>{{$c->list_votes}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <script>
                $('#parties').DataTable({"bSort": false});
            </script>
        </div>
        <div class="col">
            <h3>Load results from file</h3>
            <form action="{{route('admin.loadresults')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" id="">
            <input type="submit" value="Submit">
            </form>
            <h3>Load facesteals from file</h3>
            <form action="{{route('admin.loadfacesteals')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" id="">
            <input type="submit" value="Submit">
            </form>
        </div>
    </div>
</div>
@endsection
