@extends('layouts.admin')

@section('content')
<div class="container pt-4 pb-4">
    <h3>{{$constituency->name}}</h3>
    <p>
        @if ($next)
        <a href="{{route('admin.constituency', $next->code)}}">Go To {{$next->name}}</a>
        @endif
    </p>
    <div class="row mt-2">
        <div class="col">
            <form method="POST" action="{{route('admin.constituency.updatestats', $constituency->code)}}" class=mb-3">
                @csrf
                <div class="form-group">
                    <label for="exampleForm2">Voters</label>
                    <input name="voters" type="number" value="{{$constituency->voters}}" id="exampleForm2" class="form-control">
                </div>
                <div class="form-group">
                    <label for="exampleForm2">Turnout</label>
                    <input name="turnout" type="text" value="{{$constituency->turnout}}" id="exampleForm2" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Incumbent Party</label>
                    <select name="incumbent" type="text" value="{{$constituency->incumbent_party}}" selected="{{$constituency->incumbent_party}}"id="exampleForm2" class="form-control custom-select">
                        @php
                            $parties = \App\Party::all();
                        @endphp
                        @foreach ($parties as $p)
                            <option value="{{$p->id}}" @if($p->id == $constituency->incumbent_party) selected @endif>{{$p->name}}</option>
                        @endforeach
                    </select>
                    <small>If the incumbent party is not available, use Independents.</small>
                </div>
                <div class="form-group">
                    <label for="">Background</label>
                    <input type="url" name="background" value="{{$constituency->background}}" class="form-control" id="">
                    <small for="">URLs to images only</small>
                </div>
                <input type="submit" class="btn btn-primary" value="Update">
            </form>
            <p class="note note-warning"><strong>Warning:</strong> please click the UPDATE/SAVE button on each field before publishing.</p>
            @if ($constituency->published != true)
            <a role="button" href="{{route('admin.constituency.publish', $constituency->code)}}" class="btn btn-danger">Publish Seat Results</a>
            @else
            <a role="button" href="{{route('admin.constituency.publish', $constituency->code)}}" class="btn btn-secondary">Unpublish Seat Results</a>
            @endif
        </div>
        <div class="col">
            @foreach ($candidates as $c)
                <form action="{{route('admin.candidate.updatevotes', $c->id)}}" method="POST">
                    <tr>
                        @csrf
                        <td>{{$c->name}} ({{$c->party->code}})</td>
                        <td>
                            <div class="input-group">
                                <input name="votes"  type="number" value="{{$c->constituency_votes}}" id="exampleForm2" class="form-control">
                                <div class="input-group-append" id="{{$c->id.'append'}}">
                                    <input type="submit" class="btn btn-md btn-primary m-0 px-3 py-2 z-depth-0 waves-effect" value="Save">
                                    <a role="button" href="{{route('admin.candidate.delete', $c->id)}}" class="btn btn-md btn-danger m-0 px-3 z-depth-0 waves-effect">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </form>
            @endforeach
                Add a candidate
                <form action="{{route('admin.candidate.addsix', $constituency->code)}}" method="POST">
                    @csrf
                    <p>Fill in as many fields as needed.</p>
                    @for ($i = 0; $i < 6; $i++)
                        <div class="input-group">
                            <input name="name{{$i}}" type="text" value="" placeholder="Name" id="exampleForm2" class="form-control">
                            <select name="party{{$i}}" type="text" value=""id="exampleForm2" class="form-control custom-select">
                                @php
                                    $parties = \App\Party::all();
                                @endphp
                                @foreach ($parties as $p)
                                    <option value="{{$p->code}}">{{$p->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endfor
                    <input type="submit" class="btn btn-block mt-2 btn-sm" value="Add">
                </form>
                <br>
            Add endorsements
            <form action="{{route('admin.candidate.addendorsement', $constituency->code)}}" method="POST">
                @csrf
                <label for="">Candidate</label>
                <select name="candidate" type="text" value=""id="exampleForm2" class="form-control custom-select">
                    @foreach ($candidates as $c)
                        <option value="{{$c->id}}">{{$c->name}}</option>
                    @endforeach
                </select>
                <label for="">Party endorsing</label>
                <select name="partyendorsing" type="text" value=""id="exampleForm2" class="form-control custom-select">
                    @php
                        $parties = \App\Party::all();
                    @endphp
                    @foreach ($parties as $p)
                        <option value="{{$p->id}}">{{$p->name}}</option>
                    @endforeach
                </select>
                <input type="submit" class="btn btn-block mt-2 btn-sm" value="Add">

            </form>
        </div>
    </div>
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
    <br>
    <iframe src="{{route('constituencies.view.stream', $constituency->code)}}" frameborder="0" width="100%" height="500"></iframe>
</div>
@endsection
