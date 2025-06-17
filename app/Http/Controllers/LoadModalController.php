<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityDetail;
use App\Models\Patients;

class LoadModalController extends Controller
{
	public function editBuktiKegiatan(Request $request, ActivityDetail $activity)
	{
		$activity->load('activity');
		$patients = [];
		return view('component.modal-proof-activity', compact('activity', 'patients'));
	}

	public function editPatientActivityModal(Request $request, ActivityDetail $activity)
	{
		return view('component.modal-add-activity-patient', compact('activity'));
	}



}
