<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\InstructionGuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class InstructionGuideController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:instructions')->only('index');
        $this->middleware('permission:instruction add')->only('store');
        $this->middleware('permission:instruction edit')->only('edit', 'update');
        $this->middleware('permission:instruction delete')->only('destroy');
    }

    public function index()
    {
        $guides = InstructionGuid::orderBy('type')->get();
        return view('admin.instructions-guide.show-instructionguides', compact('guides'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'type'  => 'required|in:size_guide,washing_instructions',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        try {
            DB::beginTransaction();
            if (InstructionGuid::where('type', $data['type'])->exists()) {
                return back()->withErrors(['type' => 'This type already exists. Edit it instead.'])->withInput();
            }

            $path = $r->file('image')->store('guides', 'public');

            InstructionGuid::create([
                'type'       => $data['type'],
                'image_path' => $path,
            ]);
            DB::commit();
            return redirect()->route('instructionguides')->with('success', 'Instruction Image added Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product store error: ' . $e->getMessage());
            return back()->with('error', 'Failed to add product.');
        }
    }

    public function edit($id)
    {
        $row = InstructionGuid::findOrFail($id);
        return view('admin.instructions-guide.form', compact('row'));
    }

    public function update(Request $r, $id)
    {
        $data = $r->validate([
            'type'  => 'required|in:size_guide,washing_instructions',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        try {
            DB::beginTransaction();

            $row = InstructionGuid::findOrFail($id);

            if (
                $data['type'] !== $row->type &&
                InstructionGuid::where('type', $data['type'])->exists()
            ) {
                return back()->withErrors(['type' => 'This type already exists.'])->withInput();
            }

            if ($r->hasFile('image')) {
                if ($row->image_path && Storage::disk('public')->exists($row->image_path)) {
                    Storage::disk('public')->delete($row->image_path);
                }
                $row->image_path = $r->file('image')->store('guides', 'public');
            }

            $row->type = $data['type'];
            $row->save();

            DB::commit();
            return redirect()->route('instructionguides')->with('success', 'Image Updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('InstructionGuid update error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update image.');
        }
    }


    public function destroy($id)
    {
        try {
            $image = InstructionGuid::findOrFail($id);
            if (Storage::exists('public/' . $image->image_path)) {
                Storage::delete('public/' . $image->image_path);
            }
            $image->delete();

            return redirect()->back()
                ->with('success', 'Instruction image deleted successfully.');
        } catch (\Throwable $e) {
            Log::error('Image delete error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete image.');
        }
    }
}
