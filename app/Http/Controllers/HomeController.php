<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Nexmo;
use Carbon\Carbon;

use App\User;
use App\Models\Transaction;
use App\Models\Company;
use App\Models\Category;
use App\Models\Account;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request) {        
        config(['site.page' => 'home']);
        $company = Company::first();
        $companies = Company::all();
        $user = Auth::user();
        $search_categories = Category::pluck('id')->toArray();
        
        if($user->hasRole('user')){
            $company = $user->company;
        }else{            
            if ($request->get('company_id') != ""){   
                $selected_company = $request->get('company_id');
                $company = Company::find($selected_company);
            }   
        }
        $company_id = $company->id;
        $search_users = $company->users()->pluck('id')->toArray();
        $search_accounts = $company->accounts()->pluck('id')->toArray();
        $first_transaction = $company->transactions()->orderBy('timestamp')->first();

        // if($first_transaction){
        //     $from = date('Y-m-d', strtotime("-1 day", strtotime($first_transaction->timestamp)));
        // }else{
        //     $from = date('Y-m-d', strtotime("-1 days"));
        // }
        
        $from = Carbon::now()->startOfMonth()->format('Y-m-d');
        $to = date('Y-m-d', strtotime("+1 days"));
        $period = $spec_date = ''; 
            
        if ($request->get('spec_date') != ""){   
            $spec_date = $request->get('spec_date');
            $to = $spec_date;
        }else if ($request->get('period') != ""){   
            $period = $request->get('period');
            $from = substr($period, 0, 10);
            $to = substr($period, 14, 10);
        }
        return view('home', compact('period', 'companies', 'company', 'search_users', 'search_categories', 'search_accounts', 'company_id', 'from', 'to', 'spec_date'));
    } 
    
    public function set_pagesize(Request $request){
        $pagesize = $request->get('pagesize');
        if($pagesize == '') $pagesize = 15;
        $request->session()->put('pagesize', $pagesize);
        return back();
    }

    public function advanced_delete_request(Request $request) {
        $request_data = $request->all();
        $request_data['verification_code'] = str_random(8);
        session(['advanced_delete_request_data' => $request_data]);
        if (filter_var(Auth::user()->email, FILTER_VALIDATE_EMAIL)) {
            $to_email = Auth::user()->email;
            // Mail::to($to_email)->send(new DeleteVerification($request_data, 'Advanced Delete Verification'));
        } else {
            return response()->json(['status' => 400, 'message' => __('page.invalid_email_address')]);
        }
        $data = [
            'status' => 200,
            'data' => $request_data,
        ];
        return response()->json($data);
    }

    public function advanced_delete_verify(Request $request) {
        $request_data = session('advanced_delete_request_data');
        $verification_code = $request->get('verification_code');
        if($verification_code != $request_data['verification_code']) {
            $response_data = ['status' => 400, 'message' => __('page.incorrect_verificaiton_code')];
        } else {
            $mod = new Transaction();
            if($request_data['period'] != '') {
                $period = $request_data['period'];
                $from = substr($period, 0, 10);
                $to = substr($period, 14, 10);
                $mod = $mod->whereBetween('timestamp', [$from, $to]);
            }
            if($request_data['user'] != '') {
                $user_array = explode(',', $request_data['user']);
                $mod = $mod->whereIn('user_id', $user_array);
            }
            $purchases = $mod->delete();
            $response_data = [
                'status' => 200,
                'message' => __('page.deleted_successfully'),
            ];   
        }
        session()->forget('advanced_delete_request_datadata');
        return response()->json($response_data);
    }

    public function check_email() {
        $data = [
            'period' => '2020-01-15 to 2020-12-30',
            'supplier' => '',
            'all_users' => '0',
            'verification_code' => str_random(8),
        ];
        return view('email.delete_verification', compact('data'));
    }
}
