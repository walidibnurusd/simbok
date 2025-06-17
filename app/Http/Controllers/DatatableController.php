<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patients;
use Yajra\DataTables\Facades\DataTables;

class DatatableController extends Controller
{
	public function patients(Request $request)
	{
	    $query = Patients::with(['genderName', 'marritalStatus']);

	    return DataTables::eloquent($query)
	        ->addIndexColumn()
	        ->addColumn('tempat_tanggal_lahir', function($row) {
	            return '<p class="mb-0" style="font-size: 14px">' . e($row->place_birth) . '</p>'
	                 . '<p class="mb-0">' . e($row->dob) . ' <span class="age-red">(' . e($row->getAgeAttribute()) . ' thn)</span></p>';
	        })
			->addColumn('tempat_tanggal_lahir', function ($row) {
			    return '<p class="mb-0" style="font-size: 14px">' . e($row->place_birth) . '</p>'
			         . '<p class="mb-0">' . e($row->dob) . ' <span class="age-red">(' . e($row->getAgeAttribute()) . '-thn)</span></p>';
			})
	        ->addColumn('gender', function($row) {
	            return $row->genderName->name ?? '-';
	        })
	        ->addColumn('nikah', function($row) {
	            return $row->marritalStatus->name ?? '-';
	        })
	        ->addColumn('aksi', function($row) {
	            return '<button type="button" class="btn btn-primary btn-select-patient">Pilih</button>';
	        })
	        ->rawColumns(['tempat_tanggal_lahir', 'aksi']) // allow HTML in these columns
	        ->make(true);
	}
}
