<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('welcome');
    }

    public function submit(ContactRequest $request)
    {
        return response()->json(
            [
                'status' => 422
            ]
        );
    }

    public function postContact(ContactRequest $request)
    {
        return back();
    }
}
