<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request; 
use App\Http\Resources\MasterResource;
use App\Models\User;
use App\Models\ActivityDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class APIController extends BaseController
{
    public function docters()
    {
        $docters = User::whereHas('detail.professions', function ($query) {
            $query->where('name', 'like', '%dokter%');
        })
            ->with('detail.professions')
            ->get();

        return $this->sendResponse(MasterResource::collection($docters), 'Berhasil mengambil data dokter');
    }
   public function employeeData(Request $request)
{
    $date = $request->query('date', null); // Ambil tanggal
    $activityId = $request->query('activity_id', null); // ID aktivitas yang sedang diedit

    if (!$date) {
        return response()->json(['error' => 'Tanggal tidak ditemukan'], 400);
    }

    // Log debugging
    Log::info("Fetching employees for date: " . $date . " and activity_id: " . $activityId);

    // Ambil semua pegawai dengan role 'user'
    $employees = User::where('role', 'user')->get();

    // Ambil pegawai yang sudah memiliki aktivitas pada tanggal tertentu
    $employeesWithActivity = ActivityDetail::whereDate('date', $date)
        ->pluck('employees')
        ->toArray();

    // Konversi JSON ke array
    $excludedEmployees = [];
    foreach ($employeesWithActivity as $jsonData) {
        $excludedEmployees = array_merge($excludedEmployees, json_decode($jsonData, true));
    }

    // Jika sedang edit, ambil pegawai yang sudah dipilih sebelumnya agar tidak difilter
    $currentActivityEmployees = [];
    if ($activityId) {
        $currentActivityEmployees = ActivityDetail::where('id', $activityId)
            ->pluck('employees')
            ->first();

        if ($currentActivityEmployees) {
            $currentActivityEmployees = json_decode($currentActivityEmployees, true);
        } else {
            $currentActivityEmployees = [];
        }
    }

    // Filter pegawai yang belum memiliki aktivitas di tanggal tersebut,
    // kecuali jika mereka adalah pegawai yang sudah dipilih dalam activity_id yang sedang diedit
    $availableEmployees = $employees->reject(function ($employee) use ($excludedEmployees, $currentActivityEmployees) {
        return in_array((string) $employee->id, $excludedEmployees) && !in_array((string) $employee->id, $currentActivityEmployees);
    });

    return response()->json($availableEmployees->values());
}


}
