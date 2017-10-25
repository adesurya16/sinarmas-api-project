<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    //
    public function doLogin(Request $request) {
      if($request->password == "admin123") {
        session(['login' => true]);
        return redirect('/home');
      }

      return redirect('/login');
    }

    public function logout() {
      session(['login' => false]);

      return redirect('/login');
    }
}
