<?php

namespace Param\RBAC\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index(Request $request)
    {
        if (view()->exists($request->path())) {
            return view($request->path());
        }
        return abort(404);
    }

    /**
     * Show RBAC welcome page
     */
    public function welcome()
    {
        return view('rbac::welcome');
    }

    /**
     * Show root/home page
     */
    public function root()
    {
        return view('index');
    }

    /**
     * Handle language translation
     */
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Handle form submission
     */
    public function formSubmit(Request $request)
    {
        return view('form-repeater');
    }
}
