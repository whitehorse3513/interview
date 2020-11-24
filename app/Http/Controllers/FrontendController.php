<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\Interview;
use App\Models\Question;
use App\Models\Campaign;
use App\Models\Setting;

use Storage;
use Carbon\Carbon;
use Str;
use Image;

class FrontendController extends Controller
{
    public function index()
    {
        return view('frontend.index');
    }

    public function saveApplicant(Request $request)
    {
        $request->validate([
            'name'  =>  'required',
            'age'   =>  'required|max:8',
            'email' =>  'required',
            'phone' =>  'required',
            'image' =>  'required'
        ]);

        $slug = Str::slug($request->name)."-".$this->getRandomString();

        $currentDate = Carbon::now()->toDateString();
        $imagename = 'avatar-'.$slug.'.png';

        if(!Storage::disk('public')->exists('Avatar')){
            Storage::disk('public')->makeDirectory('Avatar');
        }

        $typeImage = Image::make($request->image)->save($imagename);
        Storage::disk('public')->put('Avatar/'.$imagename, $typeImage);

        $applicant = Applicant::updateOrCreate(
            ['email' => $request->email],
            ['image' => $imagename,'slug' => $slug, 'phone' => $request->phone, 'date' => date('Y-m-d'), 'name' => $request->name, 'age' => $request->age],
        );

        $request->session()->put('applicant', $applicant);

        return redirect()->route('test.start');
    }

    public function testStart()
    {
        if (session('applicant') == null) {
            return redirect()->route('index');
        }
        return view('frontend.test');
    }

    public function testNo()
    {
        if (session('applicant') == null) {
            return redirect()->route('index');
        }
        return view('frontend.test_no');
    }

    public function testYes()
    {
        if (session('applicant') == null) {
            return redirect()->route('index');
        }
        return view('frontend.test_yes');
    }

    public function realStart()
    {
        if (session('applicant') == null) {
            return redirect()->route('index');
        }

        $setting = Setting::first();
        if($setting == null) {
            $questions = Question::paginate(1);
        }
        else {
            $questions = Question::where('campaign_id', $setting->campaign_id)->paginate(1);
        }

        if (count($questions) == 0 ) {
            return redirect()->back()->withErrors(['No questions!', 'msg']);
        }
        return view('frontend.real', compact('questions'));
    }

    public function upload(Request $request) {
        try {
            $interview = Interview::updateOrCreate(
                ['applicant_id' => $request->applicant_id, 'question_id' => $request->question_id],
                ['file' => $request->video_filename],
            );

            if(Storage::disk('public')->exists('Interview')){
                Storage::disk('public')->delete('Interview');
            }
            $video = $request->file('video_blob');
            Storage::disk('public')->put('Interview/'.$request->video_filename, file_get_contents($video));

            $setting = Setting::first();
            if($setting == null) {
                $questions = Question::paginate(1);
            }
            else {
                $questions = Question::where('campaign_id', $setting->campaign_id)->paginate(1);
            }

            return $questions;
        } catch(Exception $ex) {
            return 'failed';
        }
    }

    public function thanks()
    {
        if (session('applicant') == null) {
            return redirect()->route('index');
        }

        return view('frontend.thanks');
    }
}
