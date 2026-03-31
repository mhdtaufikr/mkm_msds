<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    public function index()
    {
        $shops = Shop::with('documents')->get();
        return view('index', compact('shops'));
    }

    public function storeShop(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Shop::create([
            'name' => $request->name
        ]);

        return back();
    }

    public function storeDocument(Request $request)
    {
        $request->validate([
            'shop_id' => 'required',
            'files' => 'required|array',
            'files.*' => 'file|mimes:pdf'
        ]);

        foreach ($request->file('files') as $file) {
            $path = $file->store('documents', 'public');

            Document::create([
                'shop_id' => $request->shop_id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
            ]);
        }

        return back();
    }

    public function deleteDocument($id)
    {
        $doc = Document::findOrFail($id);

        // delete file dari storage
        if (Storage::disk('public')->exists($doc->file_path)) {
            Storage::disk('public')->delete($doc->file_path);
        }

        // delete dari database
        $doc->delete();

        return back();
    }

    public function show($id)
    {
        $shop = Shop::with('documents')->findOrFail($id);

        return view('show', compact('shop'));
    }

    public function preview($file)
    {
        $path = storage_path('app/public/' . $file);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
        ]);
    }
}
