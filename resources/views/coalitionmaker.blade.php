@extends('layouts.main')

@section('content')
    <div class="container pt-4">
        @php
            $parties = \App\Party::all();
        @endphp
        <h2>Coalition Maker</h2>
        <p>Mix and match parties to create your perfect parliamentary coalition.</p>
        <div class="row mb-4">
            <div class="col-md-6">
                <canvas height="100" id="graph"></canvas>
                <p id="status">Add some parties to see the coalition!</p>
            </div>
            <div class="col-md-6">
                <h5>Options</h5>
                <h6>Parties</h6>
                <div class="border p-2 grey lighten-4" id="partiesPills">
                </div>
                <!-- Basic dropdown -->
                <a class="btn bg-mhoc btn-sm dropdown-toggle mr-4" type="button" data-toggle="dropdown" aria-haspopup="true"
                   aria-expanded="false">Add a party</a>

                <div class="dropdown-menu">
                    @foreach ($parties as $p)
                        <a class="dropdown-item" href="#" onclick="addParty('{{$p->code}}')">{{$p->name}}</a>
                    @endforeach
                </div>
                <!-- Basic dropdown -->
            </div>
        </div>
    </div>
    <script>
        var parties = null;
        var addedParties = [];
        var partiesPills = null;
        $('document').ready(function () {
           parties = <?php echo(json_encode($parties)) ?>;
           partiesPills = $('#partiesPills');
           console.info(parties);
        });

        labels = [];
        colours = [];
        data = [];
        labels.push("Coalition");
        colours.push("#000");
        chart = new Chart(document.getElementById("graph"), {
            "type": "horizontalBar",
            "data": {
                "labels": labels,
                "datasets": [{
                    "data": data,
                    "fill": false,
                    "backgroundColor": colours,
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

        function addParty (code) {
            party = parties.find(obj => obj.code == code);
            console.log('Adding party:');
            console.log(party);
            if (addedParties.includes(party)) {
                alert('Already added!');
                return;
            }
            addedParties.push(party);
            var pill = $("<span></span>").addClass('badge badge-pill ml-2 ' + party.code).css("background-color", party.hex);
            $(pill).html(`${party.name} <a class='text-white' onclick='removeParty("${party.code}")' href='#'><i class='fa fa-times'></i>`);
            partiesPills.append(pill);
            console.log('Parties:');
            console.log(addedParties);
            updateGraph()
        }

        function removeParty (code) {
            party = parties.find(obj => obj.code == code)
            console.log('Removing party:');
            console.log(party);
            if (addedParties.includes(party)) {
                const index = addedParties.findIndex(x => x.code === party.code);
                if (index !== undefined) addedParties.splice(index, 1);
                console.log(addedParties);
            }
            $(`.${party.code}`).remove();
            updateGraph()
        }

        function updateGraph() {
            chart.data.labels.pop();
            chart.data.datasets.forEach((dataset) => { dataset.data.pop() })
            chart.update()
            labels = [];
            colours = [];
            data = [];
            seats = 0;
            for (i = 0; i < addedParties.length; i++) {
                party = addedParties[i];
                labels.push(party.code);
                colours.push(party.hex);
                data.push(party.list_seats);
                seats = seats + party.list_seats;
            }
            data.push(seats);
            labels.push("Coalition");
            colours.push("#000");
            console.log(colours);
            if (seats < 50) {
                $("#status").text("This coalition has " + seats + " seats, and cannot form a majority government.");
            } else {
                $("#status").text("This coalition has " + seats + " seats, and can form a majority government.");
            }
            $("#chart").html('');
            chart = new Chart(document.getElementById("graph"), {
                "type": "horizontalBar",
                "data": {
                    "labels": labels,
                    "datasets": [{
                        "data": data,
                        "fill": false,
                        "backgroundColor": colours,
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
        }
    </script>
@endsection
