<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use PDF;
class UserController extends Controller
{
    public function index()
    {
        $data = User::all();
        return view ('welcome',compact('data'));
    }
     // export PDF
     public function exportPDF() {
       
        $data = User::all();
  
        $pdf = PDF::loadView('welcome', ['data' => $data]);
        
        return $pdf->download('user.pdf');
        
      }
}
