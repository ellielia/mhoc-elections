@extends('layouts.main')
@section('content')
@php
$parties = \App\Party::all();
$list = [];
foreach ($parties as $p) {
    if ($p->seats() > 0) {
        $list[] = [
            $p->short_name,
            $p->seats(),
            $p->hex,
            $p->short_name
        ];
    }
}
@endphp
<div class="container pt-4 pb-4">
    <h2>State of the Parties</h2>
    <p>50 seats needed for a majority.</p>
    <div class="row">
        <div class="col-md-6">
            <h5>Seat share</h5>
            <canvas id="stateOfTheParties"></canvas>
        </div>
        <div class="col-md-6">
            <h5>Vote share</h5>
            <canvas id="voteShare"></canvas>
        </div>
    </div>

    <div id="parliament-diagram-live"></div>
    <script>
        createDiagram(@php echo json_encode($list) @endphp);
    </script>
    <script>
        stateOfThePartiesGraph($("#stateOfTheParties"), @php echo json_encode($sotpColours); @endphp, @php echo json_encode($sotpLabels); @endphp, @php echo json_encode($sotpSeats); @endphp);
        voteShareGraph($("#voteShare"), @php echo json_encode($sotpColours); @endphp, @php echo json_encode($sotpLabels); @endphp, @php echo json_encode($sotpVotes); @endphp);
    </script>
    @foreach($parties as $p)
        <div class="my-4 pt-2" style="border-top-width: 8px;border-top-color: {{$p->hex}};border-top-style: solid;">
            <div class="d-flex flex-row justify-content-between">
                <a data-toggle="collapse" href="#{{$p->code}}expand" aria-expanded="false" href="#{{$p->code}}" name="{{$p->code}}">
                    <h3 style="color:{{$p->hex}}">{{$p->name}}</h3>
                </a>
                <div>
                    <h4 style="text-align:right">{{number_format($p->list_votes)}} list votes • {{number_format($p->seats())}} seats</h4>
                </div>
            </div>
            <div id="{{$p->code}}expand" class="collapse">
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        @if(!$p->constituencySeats())
                        No constituencies won.
                        @endif
                        <ul style="list-style:none;" class="ml-0 pl-0">
                            @foreach($p->constituencySeats() as $seat)
                            <li>
                                <a href="{{route('constituencies.view', $seat->code)}}">
                                    <h5 class="black-text"><span class="font-weight-bold">{{$seat->name}}</span> - {{number_format($seat->winner()->constituency_votes - $seat->runnerUp()->constituency_votes)}} majority</h5>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <ul style="text-align:right; list-style:none;">
                            <li>{{$p->list_seats}} list seats</li>
                            <li>{{$p->constituencySeatCount()}} constituencies</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <ul class="list-unstyled pt-2">
        @foreach ($parties as $p)
            <li>
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="font-weight-bold" style="color: {{$p->hex}}">{{$p->name}} ({{$p->code}})</h3>
                        @if ($p->independent_grouping)
                        <span class="badge badge-dark mb-3">Independent Grouping</span>
                        @endif
                        <div class="row flex-row ml-1">
                            <div>
                                <span>List vote</span>
                                <h5>{{$p->list_votes}}</h5>
                            </div>
                            <div class="ml-4">
                                <span>List seats</span>
                                <h5>{{$p->list_seats}}</h5>
                            </div>
                            <div class="ml-4">
                                <span>Constituencies won</span>
                                <h5>{{$p->constituencySeatCount()}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@endsection
