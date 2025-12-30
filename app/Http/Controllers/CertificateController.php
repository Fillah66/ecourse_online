<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Models\MateriProgress;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class CertificateController extends Controller
{
    public function index(User $user)
    {
        $users = Certificate::where('user_id', $user->id)->get();
        return view('front.certificate', compact('users'));
    }

    public function download(Certificate $id)
    {
        $id->load('user', 'course');
        // dd($id);
        $pdf = Pdf::loadView('front.certificate.printable', [
            'data' => $id,
        ])->setPaper('a4', 'landscape');
        $filename = 'certificate_' . $id->user->name . '_' . $id->course->name . '.pdf';
        return $pdf->download($filename);
    }
}
