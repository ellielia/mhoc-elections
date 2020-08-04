<?php

namespace App\Http\Controllers;

use App\Candidate;
use App\Constituency;
use App\Endorsement;
use App\Party;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AdminController extends Controller
{
    public function redditLogin()
    {
        return Socialite::with('reddit')->redirect();
    }

    public function redditCallback()
    {
        $return = Socialite::driver('reddit')->user();
        $users = User::where(['username' => $return->getNickname()])->first();
        if($users){
            Auth::login($users);
            return redirect()->route('index');
        }else {
            $user = \App\User::create([
                'username' => $return->getNickname(),
                'admin' => false
            ]);
            $user->save();
            Auth::login($user);
            return redirect()->route('index');
        }
    }

    public function index()
    {
        $declaredCont = Constituency::where('published', true)->get();
        $undeclaredCont = Constituency::where('published', false)->get();
        $parties = Party::all()->sortByDesc('name');
        return view('admin.index', compact('declaredCont', 'undeclaredCont', 'parties'));
    }

    public function partyView($code)
    {
        $party = Party::where('code', $code)->firstOrFail();
        return view('admin.party', compact('party'));
    }

    public function updatePartySeats(Request $request, $code)
    {
        $this->validate($request, [
           'list_votes' => 'required|int',
           'list_seats' => 'required|int'
        ]);
        $party = Party::where('code', $code)->firstOrFail();
        $party->list_votes = $request->get('list_votes');
        $party->list_seats = $request->get('list_seats');
        $party->save();
        return redirect()->back()->with('success', 'Party '.$party->name.' saved.');
    }

    public function constituencyView($code)
    {
        $constituency = Constituency::where('code', $code)->firstOrFail();
        $candidates = $constituency->candidates;
        $next = Constituency::where('id', '>', $constituency->id)->first();
        return view('admin.constituency', compact('constituency', 'candidates', 'next'));
    }

    public function updateConstituencyStats(Request $request, $code)
    {
        $this->validate($request, [
            'voters' => 'required',
            'turnout' => 'required',
            'incumbent' => 'required'
        ]);
        $constituency = Constituency::where('code', $code)->firstOrFail();
        $constituency->voters = $request->get('voters');
        $constituency->turnout = $request->get('turnout');
        $constituency->incumbent_party = $request->get('incumbent');
        $constituency->background = $request->get('background');
        $constituency->save();
        return redirect()->back()->with('success', 'Constituency stats saved.');
    }

    public function updateCandidateVotes(Request $request, $id)
    {
        $this->validate($request, [
            'votes' => 'required'
        ]);
        $candidate = Candidate::whereId($id)->firstOrFail();
        $candidate->constituency_votes = $request->get('votes');
        $candidate->save();
        return redirect()->back()->with('success', 'Candidate '.$candidate->name.' saved.');
    }

    public function publishConstituency($code)
    {
        $constituency = Constituency::where('code', $code)->firstOrFail();
        if ($constituency->published != true) {
            $constituency->published = false;
            $constituency->declared = false;
            $constituency->published_at = date('Y-m-d H:i:s');
            $constituency->declared_at = date('Y-m-d H:i:s');
            $constituency->save();
            $embed_description = '';
            $runnerUpVotes = $constituency->runnerUp()->constituency_votes;
            if ($constituency->winner()->party->id == $constituency->incumbent_party) {
                $embed_description = $constituency->winner()->party->short_name.' HOLD with '.number_format($constituency->winner()->constituency_votes - $runnerUpVotes).' majority';
            } else {
                $embed_description = $constituency->winner()->party->short_name.' GAIN with '.number_format($constituency->winner()->constituency_votes - $runnerUpVotes).' majority';
            }
            $fields = [];
            foreach($constituency->candidates as $c) {
                $fields[] = array(
                    'name' => $c->name.' ('.$c->party->code.')',
                    'value' => number_format($c->constituency_votes).' votes',
                    'inline' => true
                );
            }
            $hook = json_encode([
                /*
                 * The general "message" shown above your embeds
                 */
                "content" => "**".$constituency->name."** declared",
                /*
                 * The username shown in the message
                 */
                "username" => "MHoC GEXVI Results",
                /*
                 * The image location for the senders image
                 */
                "avatar_url" => "https://GEXVI.lieselta.live/img/mhoc.png",
                /*
                 * Whether or not to read the message in Text-to-speech
                 */
                "tts" => false,
                /*
                 * File contents to send to upload a file
                 */
                // "file" => "",
                /*
                 * An array of Embeds
                 */

                "embeds" => [
                    /*
                     * Our first embed
                     */
                    [
                        // Set the title for your embed
                        "title" => $constituency->name,

                        "fields" => $fields,

                        "description" => $embed_description,

                        "color" => hexdec(substr($constituency->winner()->party->hex, 1)),

                        "thumbnail" => [
                            "url" => $constituency->background ? $constituency->background : null,
                        ],


                        "footer" =>
                        [
                            "text" => "MHoC GEXVI Results",
                            "icon_url" => "https://GEXVI.lieselta.live/img/mhoc.png"
                        ]
                    ]
                ]


            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

            $ch = curl_init();
            curl_setopt_array( $ch, [
                CURLOPT_URL => Env('DISCORD_DECLARATIONS_WEBHOOK'),
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $hook,
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json"
                ]
            ]);
            $response = curl_exec($ch);
            error_log(config('services.discord.declarations_webhook'));
            if (curl_error($ch)) {
                $error = curl_error($ch);

            }
            curl_close($ch);
            return redirect()->back()->with('success', 'Constituency published.');
        } else {
            $constituency->published = false;
            $constituency->declared = false;
            $constituency->save();
            return redirect()->back()->with('success', 'Constituency unpublished.');
        }
    }

    public function addSixCandidates(Request $request, $constituency_code)
    {
        $constituency = Constituency::where('code', $constituency_code)->firstOrFail();
        for ($i = 0; $i < 6; $i++) {
            if ($request->get('party'.$i) !== null && $request->get('name'.$i) !== null) {
                $candidate = new Candidate();
                $candidate->name = $request->get('name'.$i);
                $party = Party::where('code', $request->get('party'.$i))->first();
                if ($party === null) {
                    return redirect()->back()->with('error', 'Error in submitting candidates, related to line 119 AdminController.php');
                }
                $candidate->party_id = $party->id;
                $candidate->mp = false;
                $candidate->description = null;
                $candidate->constituency_id = $constituency->id;
                $candidate->constituency_votes = 0;
                $candidate->save();
            }
        }
        return redirect()->back()->with('success', 'Submitted candidates!');
    }

    public function addEndorsement(Request $request, $constituency_code)
    {
        $constituency = Constituency::where('code', $constituency_code)->firstOrFail();
        $endorsement = new Endorsement();
        $endorsement->constituency_id = $constituency->id;
        $endorsement->party_id = $request->get('partyendorsing');
        $endorsement->candidate_id = $request->get('candidate');
        $endorsement->save();
        return redirect()->back()->with('success', 'Added endorsement!');
    }

    public function deleteCandidate($id)
    {
        $c = Candidate::whereId($id)->firstOrFail();
        $c->delete();
        return redirect()->back()->with('info', 'Deleted candidate!');
    }

    public function loadResultsFromFile(Request $request)
    {
        $this->validate($request, [
            'file' => 'required',
        ]);
        $uploadedFile = $request->file('file')->get();
        echo('Processing..');
        $results = json_decode($uploadedFile);
        foreach($results as $r) {
            error_log(json_encode($r));
            echo('<br>');
            echo(json_encode($r));
            $constituency = Constituency::where(strtolower('name'), strtolower($r->constituency))->first();
            if (!$constituency) { echo (' -- FAIL'); continue; }
            $candidates = $constituency->candidates;
            foreach ($candidates as $c) {
                switch (strtolower($c->party->code)) {
                    case 'con':
                        $c->constituency_votes = $r->CON;
                    break;
                    case 'lab':
                        $c->constituency_votes = $r->LAB;
                    break;
                    case 'ld':
                        $c->constituency_votes = $r->LD;
                    break;
                    case 'lpuk':
                        $c->constituency_votes = $r->LPUK;
                    break;
                    case 'drf':
                        $c->constituency_votes = $r->DRF;
                    break;
                    case 'll':
                        $c->constituency_votes = $r->LL;
                    break;
                    case 'tpm':
                        $c->constituency_votes = $r->TPM;
                    break;
                    case 'snp':
                        $c->constituency_votes = $r->SNP;
                    break;
                }
                $c->save();
            }
            $constituency->turnout = $r->turnout;
            $constituency->save();
            echo(" -- FINISHED");
        }
    }

    public function loadFacestealsFromFile(Request $request)
    {
        $this->validate($request, [
            'file' => 'required',
        ]);
        $uploadedFile = $request->file('file')->get();
        echo('Processing..');
        $facesteals = json_decode($uploadedFile);
        foreach ($facesteals as $person)
        {
            error_log(json_encode($person));
            echo('<br>');
            echo(json_encode($person));
            $candidate = Candidate::where(strtolower('name'), strtolower($person->Name))->first();
            if (!$candidate) { echo (" -- NOT CANDIDATE"); continue; }
            $candidate->picture = $person->URL;
            echo(" -- DONE");
            $candidate->save();
        }
    }
}
