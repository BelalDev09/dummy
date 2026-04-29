<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Traits\apiresponse;
use Illuminate\Http\Request;

class NewsLetterController extends Controller
{
    use apiresponse;
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email',
        ]);

        try {

            $newsletter = Newsletter::firstOrCreate(
                ['email' => $request->email],
                ['status' => true]
            );

            if (!$newsletter->wasRecentlyCreated) {
                return $this->error('Already subscribed', 409);
            }

            return $this->success('Subscribed successfully', $newsletter, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }


    public function index()
    {
        $data = Newsletter::latest()->paginate(20);

        return $this->success('All newsletters', $data);
    }


    public function toggleStatus($id)
    {
        try {

            $newsletter = Newsletter::findOrFail($id);
            $newsletter->status = !$newsletter->status;
            $newsletter->save();

            return $this->success('Status updated', $newsletter);
        } catch (\Exception $e) {
            return $this->error('Not found', 404);
        }
    }

    public function destroy($id)
    {
        try {

            $newsletter = Newsletter::findOrFail($id);
            $newsletter->delete();

            return $this->success('Deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Not found', 404);
        }
    }
}
