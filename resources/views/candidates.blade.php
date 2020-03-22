@extends('layouts.main')
@section('title', 'Candidates')
@section('description', 'View all candidates in the election')

@section('content')
<div class="container pt-4">
    <h2>Candidates</h2>
    <!-- Search form -->
    <div class="row">
        <div class="col-md-10">

            <form class="md-form mt-2">
                <input id="filterInput" onkeypress="filter(this.value)" class="form-control" type="text" placeholder="Filter candidates by name" aria-label="Search">
            </form>
        </div>
        <div class="col-md-2">
            <button onclick="clearFilter()" class="btn btn-sm">Clear Filter</button>
        </div>
    </div>
    <script>
        function filter(query) {
            console.log('Filtering for ' + query);
            var cards = document.getElementsByClassName('candidate-card');
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
            var cards = document.getElementsByClassName('candidate-card');
            for (i = 0; i < cards.length; i++) {
                card = cards[i];
                card.style.display = "";
            }
            document.getElementById('filterInput').value = "";
        }
    </script>
    @foreach ($candidates as $c)
        <div class="row mb-3 candidate-card" id="{{$c->name}}">
            <div class="col-md-12">
                <div class="card" style="border-bottom-width: 8px;border-bottom-color: {{$c->party->hex}};border-bottom-style: solid;">
                    <div class="card-header">
                        {{$c->name}}
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-row align-items-center">
                            <a href="{{$c->picture}}">
                                <div style="width: 150px; !important; height: 150px !important; background:url({{$c->picture}}); background-size: cover; background-position: center; border-radius: .25rem 0 0 .25rem;"></div>
                            </a>
                            <div class="card-body d-flex flex-column align-items-left justify-content-center">
                            <h5 style="color: {{$c->party->hex}}">{{$c->party->name}}</h5>
                            <h6>Running in <a href="{{route('constituencies.view', $c->constituency->code)}}">{{$c->constituency->name}}</a></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
