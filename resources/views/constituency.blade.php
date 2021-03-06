@extends('layouts.main')
@section('title', $constituency->name)
@section('description', $constituency->region.' Constituency')
@section('content')
<div class="card card-image" style="border-radius: 0; background-color: @if($constituency->declared){{$constituency->winner()->party->hex}}@else #006B3E @endif; background-image: url({{$constituency->background}}); background-size: cover; background-position: center; height: 200px;">
    <div class="text-white text-left rgba-black-light" style="height: 100%;">
        <div class="container d-flex flex-row justify-content-between align-items-end h-100">
            <div class="pb-2">
                <h2 class="text-shadow">{{$constituency->name}}</h2>
                <h5>{{$constituency->region}}</h5>
            </div>
            @if($constituency->declared)
            <div class="pb-2">
                <h2>
                    <span class="badge" style="background-color: {{$constituency->winner()->party->hex}};@if($constituency->winner()->code == "SNP") color: black !important; @endif">
                        @if ($constituency->winner()->party->id == $constituency->incumbent_party)
                            {{$constituency->winner()->party->code}} Hold
                        @else
                            {{$constituency->winner()->party->code}} Gain from {{\App\Party::whereId($constituency->incumbent_party)->firstOrFail()->code}}
                        @endif
                    </span>
                </h2>
            </div>
            @else
            <div class="pb-2">
                <h2>
                    <span class="badge" style="background-color: {{\App\Party::whereId($constituency->incumbent_party)->firstOrFail()->hex}}; @if(\App\Party::whereId($constituency->incumbent_party)->firstOrFail()->code == "SNP") color: black !important; @endif">
                        Incumbent: {{\App\Party::whereId($constituency->incumbent_party)->firstOrFail()->short_name}}
                    </span>
                </h2>
            </div>
            @endif
        </div>
    </div>
</div>
<div class="container py-4">
    @if($constituency->declared)
    <div class="row m-0 pb-3">
        <div class="col-md-6">
            <div class="card" style="height: 150px;">
                <div class="d-flex flex-row align-items-center">
                    <div style="width: 150px; !important; height: 150px !important; background:url({{$constituency->winner()->picture}}); background-size: cover; background-position: center; border-radius: .25rem 0 0 .25rem;"></div>
                    <div class="card-body d-flex flex-column align-items-left justify-content-center">
                        <h3 style="color: {{$constituency->winner()->party->hex}}"><i class="fa fa-check"></i>&nbsp;<b>{{$constituency->winner()->name}}</b>@if($constituency->winner()->mp) <span style="background-color: {{$constituency->winner()->party->hex}}; color: #fff;" class="px-2">MP</span> @endif</h3>
                        <h5 style="color: {{$constituency->winner()->party->hex}}">{{$constituency->winner()->party->short_name}}</h5>
                        <p style="word-wrap:initial;">Leads by {{number_format($constituency->winner()->constituency_votes - $constituency->runnerUp()->constituency_votes)}}  </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
                <canvas height="100" id="horizontalBar"></canvas>
                @php
                    $partiesLabels = array();
                    $partiesColours = array();
                    $votes = array();
                    foreach ($constituency->candidates as $c) {
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
                                display: false,
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
    Results yet to be declared
    @endif
    @foreach($constituency->candidates as $c)
    <div class="my-4 pt-2" style="border-top-width: 8px;border-top-color: {{$c->party->hex}};border-top-style: solid;">
        <div class="d-flex flex-row justify-content-between">
            <div class="d-flex flex-row justify-content-left">
                <img src="{{$c->party->logo}}" style="height: 100px; margin-right: 10px;" alt="">
                <a href="/stateoftheparties#{{$c->party->code}}">
                    <h3 style="color:{{$c->party->hex}}">{{$c->party->short_name}}</h3>
                </a>
            </div>
            <div>
                <a data-toggle="modal" data-target="#{{$c->id}}PicModal"><h4 style="text-align:right;">{{$c->name}}</h4></a>
                <ul style="text-align:right; list-style:none;">
                    @if($constituency->declared)
                    <li>Votes: {{number_format($c->constituency_votes)}}</li>
                    <li>Vote share: {{number_format($c->constituency_votes / $constituency->totalVotes() * 100, 1)}}%</li>
                    @php
                    $vote_share = number_format(($c->constituency_votes / $constituency->totalVotes() * 100) - ($c->previous_constituency_votes / $constituency->totalVotes() * 100), 1)
                    @endphp
                    <li title="GEXII: {{number_format($c->previous_constituency_votes)}}">Vote share change: @if($vote_share > 0)+@endif{{$vote_share}}</li>
                    @endif
                    @if ($c->previous_constituency_votes > 0)
                        <li class="text-muted">GEXII: {{number_format($c->previous_constituency_votes)}}</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    @endforeach
    <div style="text-align:right;" class="text-muted">Change compared with GEXII</div>
    <hr>
    @if($constituency->declared)
    <p style="text-align:right;" class="text-muted">Declared {{$constituency->declaredAtHuman()}} ({{$constituency->declaredAtPretty()}})</p>
    @endif
    <h3>Endorsements</h3>
    <div class="row">
        @foreach ($constituency->candidates as $c)
            <div class="col py-2" style="border-top-width: 8px;border-top-color: {{$c->party->hex}};border-top-style: solid;">
                <h4>{{$c->name}}</h4>
                @if(count($c->endorsements) == 0)
                No endorsements.
                @endif
                <ul class="ml-0 pl-0 list-unstyled">
                    @foreach($c->endorsements as $e)
                        <li class="mb-2"><img src="{{$e->party->logo}}" alt="" style="height: 30px;"> {{$e->party->short_name}}</li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

    @foreach($constituency->candidates as $c)
    <div class="modal fade" id="{{$c->id}}PicModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <img src="{{$c->picture}}">
            </div>
        </div>
    </div>
    @endforeach
@endsection
