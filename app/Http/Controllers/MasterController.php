<?php

namespace App\Http\Controllers;

use App\Http\Resources\MasterResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Profession;
use App\Models\Position;
use App\Models\Rank;
use App\Models\Program;
use App\Models\Service;
use App\Models\Activity;
use App\Models\ActivityDetail;
use App\Models\ActivityPatient;
use App\Models\ActivityProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterController extends Controller
{
    public function profession()
    {
        $professions = Profession::all();
        return view('content.master.profession', ['professions' => $professions]);
    }
    public function storeProfession(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Create a new profession instance
            $profession = new Profession();

            // Assign the validated data to the profession model
            $profession->name = $validatedData['name'];

            // Save the profession to the database
            $profession->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Data profesi berhasil ditambahkan');
        } catch (Exception $e) {
            // Log the error
            Log::error('Error storing profession: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors('An error occurred while saving the profession. Please try again.');
        }
    }

    public function updateProfession(Request $request, $id)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $profession = Profession::findOrFail($id);

            $profession->name = $validatedData['name'];

            $profession->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Data profesi berhasil diubah');
        } catch (Exception $e) {
            // Log the error
            Log::error('Error updating profession: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors('An error occurred while updating the profession. Please try again.');
        }
    }

    public function destroyProfession($id)
    {
        $profession = Profession::findOrFail($id);

        // Handle foreign key constraint by nullifying or deleting related records
        DB::table('user_details')
            ->where('profession', $id)
            ->update(['profession' => null]);

        // Delete the profession after handling the foreign key constraint
        $profession->delete();

        return redirect()->back()->with('success', 'Data profesi berhasil dihapus');
    }
    public function position()
    {
        $positions = Position::all();
        return view('content.master.position', ['positions' => $positions]);
    }
    public function storePosition(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $position = new Position();

            $position->name = $validatedData['name'];

            $position->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Data jabatan berhasil ditambahkan');
        } catch (Exception $e) {
            // Log the error
            Log::error('Error storing position: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors('An error occurred while saving the position. Please try again.');
        }
    }

    public function updatePosition(Request $request, $id)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $position = Position::findOrFail($id);

            $position->name = $validatedData['name'];

            $position->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Data jabatan berhasil diubah');
        } catch (Exception $e) {
            // Log the error
            Log::error('Error updating position: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors('An error occurred while updating the position. Please try again.');
        }
    }

    public function destroyPosition($id)
    {
        $position = Position::findOrFail($id);

        DB::table('user_details')
            ->where('position', $id)
            ->update(['position' => null]);

        $position->delete();

        return redirect()->back()->with('success', 'Data pangkat berhasil dihapus');
    }
    public function rank()
    {
        $ranks = Rank::all();
        return view('content.master.rank', ['ranks' => $ranks]);
    }
    public function storeRank(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $rank = new Rank();

            $rank->name = $validatedData['name'];

            $rank->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Data pangkat berhasil ditambahkan');
        } catch (Exception $e) {
            // Log the error
            Log::error('Error storing rank: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors('An error occurred while saving the rank. Please try again.');
        }
    }

    public function updateRank(Request $request, $id)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $rank = Rank::findOrFail($id);

            $rank->name = $validatedData['name'];

            $rank->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Data pangkat berhasil diubah');
        } catch (Exception $e) {
            // Log the error
            Log::error('Error updating rank: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors('An error occurred while updating the rank. Please try again.');
        }
    }

    public function destroyRank($id)
    {
        $rank = Rank::findOrFail($id);

        DB::table('user_details')
            ->where('rank', $id)
            ->update(['rank' => null]);

        $rank->delete();

        return redirect()->back()->with('success', 'Data pangkat berhasil dihapus');
    }
    public function program()
    {
        $programs = Program::all();
        return view('content.master.program', ['programs' => $programs]);
    }
    public function storeProgram(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $program = new Program();

            $program->name = $validatedData['name'];

            $program->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Data kegiatan berhasil ditambahkan');
        } catch (Exception $e) {
            // Log the error
            Log::error('Error storing program: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors('An error occurred while saving the program. Please try again.');
        }
    }

    public function updateProgram(Request $request, $id)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $program = Program::findOrFail($id);

            $program->name = $validatedData['name'];

            $program->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Data program berhasil diubah');
        } catch (Exception $e) {
            // Log the error
            Log::error('Error updating program: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors('An error occurred while updating the program. Please try again.');
        }
    }
    public function destroyProgram($id)
    {
        $program = Program::findOrFail($id);

        // Setelah semua activity dan data terkait terhapus, hapus program
        $program->delete();

        return redirect()->back()->with('success', 'Program dan semua kegiatan terkait berhasil dihapus.');
    }

    public function service()
    {
        $services = Service::all();
        return view('content.master.service', ['services' => $services]);
    }
    public function storeService(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $service = new Service();

            $service->name = $validatedData['name'];

            $service->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Data layanan berhasil ditambahkan');
        } catch (Exception $e) {
            // Log the error
            Log::error('Error storing service: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors('An error occurred while saving the service. Please try again.');
        }
    }

    public function updateService(Request $request, $id)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $service = Service::findOrFail($id);

            $service->name = $validatedData['name'];

            $service->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Data layanan berhasil diubah');
        } catch (Exception $e) {
            // Log the error
            Log::error('Error updating layanan: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors('An error occurred while updating the layanan. Please try again.');
        }
    }

    public function destroyService($id)
    {
        $service = Service::findOrFail($id);

        $service->delete();

        return redirect()->back()->with('success', 'Data layanan berhasil dihapus');
    }
}
