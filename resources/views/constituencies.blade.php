@extends('layouts.main')
@section('title', 'Constituencies')
@section('description', 'The constituencies being contested this election')
@section('content')
<div class="container pt-4">
    <h2>Constituencies</h2>
    <!-- Search form -->
    <div class="row">
        <div class="col-md-10">

            <form class="md-form mt-2">
                <input id="filterInput" onkeypress="filter(this.value)" class="form-control" type="text" placeholder="Filter constituencies by name" aria-label="Search">
            </form>
        </div>
        <div class="col-md-2">
            <button onclick="clearFilter()" class="btn btn-sm">Clear Filter</button>
        </div>
    </div>
    <script>
        function filter(query) {
            console.log('Filtering for ' + query);
            var cards = document.getElementsByClassName('constituency-card');
            for (i = 0; i < cards.length; i++) {
                card = cards[i];
                if (card) {
                    constituencyName = card.id;
                    if (constituencyName.toLowerCase().indexOf(query.toLowerCase()) > -1) {
                        card.style.display = "";
                    } else {
                        card.style.display = "none";
                    }
                }
            }
        }

        function clearFilter() {
            var cards = document.getElementsByClassName('constituency-card');
            for (i = 0; i < cards.length; i++) {
                card = cards[i];
                card.style.display = "";
            }
            document.getElementById('filterInput').value = "";
        }
    </script>
    @foreach ($constituencies as $c)
        <div class="row mb-3 constituency-card" id="{{$c->name}}">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{$c->name}}
                    </div>
                    <div class="card-body">
                        @if ($c->published)
                        <div class="row">
                            <div class="col">
                                <h5 style="color: {{$c->winner()->party->hex}}"><i class="fa fa-check"></i>&nbsp;<b>{{$c->winner()->name}}</b></h5>
                                <h5 style="color: {{$c->winner()->party->hex}}">{{$c->winner()->party->short_name}}</h5>
                                <h3>
                                    <span class="badge" style="background-color: {{$c->winner()->party->hex}}">
                                        @if ($c->winner()->party->id == $c->incumbent_party)
                                            {{$c->winner()->party->code}} Hold
                                        @else
                                            {{$c->winner()->party->code}} Gain from {{\App\Party::whereId($c->incumbent_party)->firstOrFail()->code}}
                                        @endif
                                    </span>
                                </h3>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <span>Leading by<br/><h5>{{number_format($c->winner()->constituency_votes - $c->runnerUp()->constituency_votes)}}</h5></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <span>Runner-up<br/><h5>{{$c->runnerUp()->name}} ({{$c->runnerUp()->party->code}})</h5></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <canvas height="100" id="horizontalBar{{$c->code}}"></canvas>
                                @php
                                    $partiesLabels = array();
                                    $partiesColours = array();
                                    $votes = array();
                                    array_push($partiesLabels, $c->winner()->party->code);
                                    array_push($partiesLabels, $c->runnerUp()->party->code);
                                    array_push($partiesColours, $c->winner()->party->hex);
                                    array_push($partiesColours, $c->runnerUp()->party->hex);
                                    array_push($votes, $c->winner()->constituency_votes);
                                    array_push($votes, $c->runnerUp()->constituency_votes);
                                @endphp
                                <script>
                                    var labels = @php echo json_encode($partiesLabels); @endphp;
                                    var backgroundColours = @php echo json_encode($partiesColours); @endphp;
                                    var data = @php echo json_encode($votes); @endphp;
                                    console.log(labels);
                                    new Chart(document.getElementById("horizontalBar{{$c->code}}"), {
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
                            </div>
                        </div>
                        @else
                        No results published. Currently held by {{\App\Party::whereId($c->incumbent_party)->firstOrFail()->name}}.
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{route('constituencies.view', $c->code)}}">Details</a>
                        @if ($c->published)
                            @php
                                $timeago = \Carbon\Carbon::create($c->declared_at)->diffForHumans();
                            @endphp
                            · Published {{$timeago}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection