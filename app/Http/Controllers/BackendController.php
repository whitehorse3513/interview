<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Auth;
use App\Models\Applicant;
use App\Models\Interview;
use App\Models\Question;
use App\Models\Campaign;
use App\Models\Setting;
use App\Models\Company;

use Session;

class BackendController extends Controller
{
    public function loginShow()
    {
        return view('backend.login');
    }

    public function authenticate(Request $request)
    {
        $validator = $request->validate([
            'email'     => 'required',
            'password'  => 'required|min:6'
        ]);

        $rememberMe = false;

        if(isset($request->remember)) {
            $rememberMe = true;
        }

        if (Auth::attempt($validator, $rememberMe)) {
            return redirect('/home');
        }
        else {
            return redirect()->back();
        }
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return back();
    }

    public function dashboard(Request $request)
    {
        if ($request->sort)
        {
            $sort = $request->sort;
        }
        else {
            $sort = 'ranking';
        }

        switch ($sort) {
            case 'ranking':
                $orderBy = 'mark';
                break;
            case 'age':
                $orderBy = 'age';
                break;
            case 'date':
                $orderBy = 'date';
                break;
        }

        $applicants = Applicant::latest()->paginate(12);
        foreach ($applicants as $key => $applicant) {
            $mark_sum = Interview::where('applicant_id', $applicant->id)->sum('rate');
            $mark_count = Interview::where('applicant_id', $applicant->id)->count('rate');
            if ($mark_count == null)
                $mark = 0;
            else {
                $mark = $mark_sum / $mark_count;
            }
            $applicant->mark = $mark;
        }


        $applicants->setCollection(
            $applicants->sortBy(function ($applicant) use($orderBy) {
                return $applicant[$orderBy];
            })
        );

        return view('backend.dashboard', compact('applicants', 'sort'));
    }

    public function deleteApplicant($id)
    {
        $applicant = Applicant::find($id);

        $interviews = Interview::where('applicant_id', $id)->get();

        foreach ($interviews as $key => $interview) {
            $interview->delete();
        }

        $applicant->delete();

        return redirect()->back();
    }

    public function showVideos(string $slug)
    {
        $applicant = Applicant::where('slug', $slug)->first();

        if($applicant == null)
            return redirect()->route('dashboard');

        $mark = Interview::where('applicant_id', $applicant->id)->avg('rate');
        if ($mark == null)
            $mark = 0;
        $applicant->mark = $mark;

        $interviews = Interview::where('applicant_id', $applicant->id)->latest()->get();

        return view('backend.video', compact('applicant', 'interviews'));
    }

    public function showSettings()
    {
        return view('backend.setting');
    }

    public function saveComment(Request $request)
    {
        try {
            $interview = Interview::find($request->id);
            $interview->comment = $request->comment;
            $interview->rate = $request->rate;
            $interview->save();

            return response()->json(
                [
                  'success' => true,
                  'message' => 'Comment saved successfully'
                ]
            );
        } catch (Exception $ex) {
            return response()->json(
                [
                  'success' => fase,
                  'message' => 'Something went wrong!'
                ]
            );
        }
    }

    public function companies() {
        $companies = Company::latest()->get();

        return request()->ajax() ? response()->json($companies,Response::HTTP_OK) : abort(404);
    }

    public function createCompany(Request $request) {
        Company::updateOrCreate(
            [
              'id' => $request->id
            ],
            [
              'name' => $request->name
            ]
          );

          return response()->json(
            [
              'success' => true,
              'message' => 'Data inserted successfully'
            ]
          );
    }

    public function updateCompany($id)
    {
        $company  = Company::find($id);

        return response()->json([
            'data' => $company
        ]);
    }

    public function deleteCompany($id)
    {
        $company = Company::find($id);

        $company->delete();

        return response()->json([
        'message' => 'Data deleted successfully!'
        ]);
    }

    public function campaigns() {
        $campaigns = Campaign::latest()->get();

        return request()->ajax() ? response()->json($campaigns,Response::HTTP_OK) : abort(404);
    }

    public function createCampaign(Request $request) {
        Campaign::updateOrCreate(
            [
              'id' => $request->id
            ],
            [
              'name' => $request->name
            ]
          );

          return response()->json(
            [
              'success' => true,
              'message' => 'Data inserted successfully'
            ]
          );
    }

    public function updateCampaign($id)
    {
        $campaign  = Campaign::find($id);

        return response()->json([
            'data' => $campaign
        ]);
    }

    public function deleteCampaign($id)
    {
        $campaign = Campaign::find($id);

        $questions = Question::where('campaign_id', $id)->get();

        foreach ($questions as $key => $question) {
            $question -> delete();
        }

        $campaign->delete();

        return response()->json([
        'message' => 'Data deleted successfully!'
        ]);
    }

    public function questions() {
        $questions = Question::latest()->get();

        foreach ($questions as $key => $question) {
            $question->campaign_name = Campaign::where('id', $question->campaign_id)->first()['name'];
        }

        return request()->ajax() ? response()->json($questions,Response::HTTP_OK) : abort(404);
    }

    public function createQuestion(Request $request) {
        Question::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'campaign_id' => $request->campaign_id,
                'name' => $request->name
            ]
        );

        return response()->json(
            [
              'success' => true,
              'message' => 'Data inserted successfully'
            ]
        );
    }

    public function updateQuestion($id)
    {
        $question  = Question::find($id);

        $question->campaign_name = Campaign::where('id', $question->campaign_id)->first()['name'];

        return response()->json([
            'data' => $question
        ]);
    }

    public function deleteQuestion($id)
    {
        $question = Question::find($id);

        $question->delete();

        return response()->json([
        'message' => 'Data deleted successfully!'
        ]);
    }

    public function setting() {
        $setting = Setting::first();

        return request()->ajax() ? response()->json($setting,Response::HTTP_OK) : abort(404);
    }

    public function saveSetting(Request $request) {
        $setting = Setting::updateOrCreate(
            ['id' => '1'],
            [
            'campaign_id' => $request->campaign_id
            ]
        );

        return response()->json(
            [
              'success' => true,
              'message' => 'Data updated successfully'
            ]
        );
    }
}
