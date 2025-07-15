<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function apiResponse($statusCode = 200, $message = '', $data = [])
    {
        return response()->json([
            'status' => $statusCode,
            'message' => $message,
            'data' => $data,
            // 'error' => $error
        ], $statusCode);
    }

    public function uploadAttachment(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Validation failed',
                    'data' => [],
                    'error' => $validator->errors()
                ], 422);
            }

            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment')->store('attachments', 'public');
                $url = asset('storage/' . $path);
                return $this->apiResponse(200, 'Attachment uploaded successfully', ['url' => $url]);
            }

            return response()->json([
                'status' => 400,
                'message' => 'No file uploaded',
                'data' => [],
                'error' => ['attachment' => ['File is missing or not attached']]
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Server error during file upload',
                'data' => [],
                'error' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }

}
