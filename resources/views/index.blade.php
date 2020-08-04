@extends('layouts.main')
@section('title', 'Home')
@section('description', 'Results of MHoC\'s 14th General Election')
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
<div class="jumbotron jumbotron-fluid pt-4 pb-0">
    <div class="container pb-0">
        <div class="row">
            <div class="col-md-6">
                @if (count($declaredseats) < 1)
                <h3>When seats are declared, they will appear here and down below.</h3>
                @else
                    @php
                        $latestResult = \App\Constituency::where('declared', true)->get()->sortByDesc('declared_at')->first();
                    @endphp
                <h3><b>Latest result</b></h3>
                <div class="row">
                    <div class="col-md-6 text-left">
                        <h5>{{$latestResult->name}}</h5>
                        <h3 class="pt-2" style="color: {{$latestResult->winner()->party->hex}}"><i class="fa fa-check"></i>&nbsp;<b>{{$latestResult->winner()->name}}</b></h3>
                        <h5>{{$latestResult->winner()->party->short_name}}</h5>
                    </div>
                    <div class="col">
                        <h3 class="">
                            <span class="badge" style="background-color: {{$latestResult->winner()->party->hex}}">
                                @if ($latestResult->winner()->party->id == $latestResult->incumbent_party)
                                    {{$latestResult->winner()->party->code}} Hold
                                @else
                                    {{$latestResult->winner()->party->code}} Gain from {{\App\Party::whereId($latestResult->incumbent_party)->firstOrFail()->code}}
                                @endif
                            </span>
                        </h3>
                        <p class="mt-3">
                            Majority:
                            @php
                            $runnerUpVotes = $latestResult->runnerUp()->constituency_votes;
                            echo(number_format($latestResult->winner()->constituency_votes - $runnerUpVotes));
                            @endphp<br/>
                            Turnout:
                            @php
                            echo($latestResult->turnout ."%");
                            @endphp
                        </p>
                    </div>
                </div>
                <canvas height="100" id="horizontalBar"></canvas>
                @php
                $partiesLabels = array();
                $partiesColours = array();
                $votes = array();
                foreach ($latestResult->candidates as $c) {
                    $code = $c->party->code;
                    array_push($partiesLabels, $code);
                    $colour = $c->party->hex;
                    array_push($partiesColours, $colour);
                    $v = $c->constituency_votes;
                    array_push($votes, $v);
                }
                @endphp
                <script>
                    var labels = @php echo json_encode($partiesLabels); @endphp;
                    var backgroundColours = @php echo json_encode($partiesColours); @endphp;
                    var data = @php echo json_encode($votes); @endphp;
                    console.log(labels);
                    new Chart(document.getElementById("horizontalBar"), {
                        "type": "horizontalBar",
                        "data": {
                            "labels": labels,
                            "datasets": [{
                                "data": data,
                                "fill": false,
                                "backgroundColor": backgroundColours,
                                "borderWidth": 1
                            }]
                        },
                        "options": {
                            "legend": {
                                display: false
                            },
                            "scales": {
                                "xAxes": [{
                                    "ticks": {
                                        "beginAtZero": true
                                    }
                                }]
                            }
                        }
                    });
                </script>

                <a href="{{route('constituencies.view', $latestResult->code)}}" role="button" class="btn btn-link btn-block mt-3 btn-sm">View Constituency</a>
                @endif
            </div>
            <div class="col-md-6">
                <h3><b>Live seat map</b></h3>
                <div id="parliament-diagram-live"></div>
                <script>
                    createDiagram(@php echo json_encode($list) @endphp);
                </script>
            </div>
        </div>
    </div>
    <div class="orange" style="visibility: collapse">
        <div class="container pt-2 pb-2 text-center">
            <h4 class="p-0 m-0"><i class="fa fa-warning"></i>&nbsp;New result released! <a class="text-dark underline font-weight-bold" href=""><u>Refresh</u></a></h4>
        </div>
    </div>
</div>
<div class="container pb-5">
    <h4>State of the parties</h4>
    <p>50 seats needed for a majority.</p>
    <canvas height="120" id="stateOfTheParties"></canvas>
    <script>
        stateOfThePartiesGraph($("#stateOfTheParties"), @php echo json_encode($sotpColours); @endphp, @php echo json_encode($sotpLabels); @endphp, @php echo json_encode($sotpSeats); @endphp)
    </script>
    <h4 class="mt-2">Recent seat results</h4>
    <div class="card-columns">
        @if(count($declaredseats) == 0)
        None yet!
        @endif
        @foreach ($declaredseats as $c)
        <div class="card mt-2 mb-3">
            <div class="card-body">
                <h5 class="card-title">{{$c->name}}</h5>
                <h3 style="color: {{$c->winner()->party->hex}}"><i class="fa fa-check"></i>&nbsp;<b>{{$c->winner()->name}}</b></h3>
                <h3>
                    <span class="badge" style="background-color: {{$c->winner()->party->hex}}">
                        @if ($c->winner()->party->id == $c->incumbent_party)
                        {{$c->winner()->party->code}} Hold
                    @else
                        {{$c->winner()->party->code}} Gain from {{\App\Party::whereId($c->incumbent_party)->firstOrFail()->code}}
                    @endif
                    </span>
                </h3>
                <p class="mt-3">
                    Majority:
                    @php
                        $runnerUpVotes = $c->runnerUp()->constituency_votes;
                        echo(number_format($c->winner()->constituency_votes - $runnerUpVotes));
                    @endphp<br/>
                    Runner-up: {{$c->runnerUp()->name}} ({{$c->runnerUp()->party->code}})<br/>
                    Turnout:
                    @php
                        echo($c->turnout ."%");
                    @endphp
                </p>
                <a href="{{route('constituencies.view', $c->code)}}" role="button" class="btn btn-block btn-sm">Details</a>
            </div>
            <div class="card-footer">
                @php
                    $timeago = \Carbon\Carbon::create($c->declared_at)->diffForHumans();
                @endphp
                <small title="{{$c->declaredAtPretty()}}" class="text-muted">Declared {{$timeago}}</small>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
