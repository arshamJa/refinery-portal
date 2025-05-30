<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\TaskUser;
use App\Models\TaskUserFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TaskController extends Controller
{
//    public function submitTaskForm(Request $request, $taskUserId)
//    {
//        $request->validate([
//            'taskBody' => ['required', 'string', 'min:5'],
//            'fileUpload.*' => ['nullable', 'file']
//        ]);
//
//        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
//
//        $newTime = sprintf("%04d/%02d/%02d", $ja_year, $ja_month, $ja_day);
//
//        $body = Str::of($request->input('taskBody'))->squish();
//
//        $taskUser = TaskUser::where('id', $taskUserId)
//            ->where('user_id', Auth::id())
//            ->firstOrFail();
//
//        $taskUser->update([
//            'sent_date' => $newTime,
//            'is_completed' => true,
//            'body_task' => $body,
//        ]);
//
//        if ($request->hasFile('fileUpload')) {
//            foreach ($request->file('fileUpload') as $file) {
//                $path = $file->store('task_files', 'public');
//                TaskUserFile::create([
//                    'task_user_id' => $taskUser->id,
//                    'file_path' => $path,
//                    'original_name' => $file->getClientOriginalName(),
//                ]);
//            }
//        }
//
//        return redirect()->back()->with('status', 'اقدام با موفقیت ثبت شد');
//    }
//
//    public function update(Request $request, $taskUserId)
//    {
//        $request->validate([
//            'taskBody' => ['required', 'string', 'min:5'],
//            'fileUpload.*' => ['nullable', 'file', 'max:2048']
//        ]);
//
//        $taskUser = TaskUser::where('id', $taskUserId)
//            ->where('user_id', auth()->id())
//            ->firstOrFail();
//
//        $taskUser->update([
//            'body_task' => $request->taskBody,
//        ]);
//
//        if ($request->hasFile('fileUpload')) {
//            foreach ($request->file('fileUpload') as $file) {
//                $path = $file->store('task_files', 'public');
//                TaskUserFile::create([
//                    'task_user_id' => $taskUser->id,
//                    'file_path' => $path,
//                    'original_name' => $file->getClientOriginalName(),
//                ]);
//            }
//        }
//
//        return redirect()->back()->with('status', 'اقدام با موفقیت ویرایش شد');
//    }

//    public function deleteFile($fileId)
//    {
//        $file = TaskUserFile::findOrFail($fileId);
//
//        // Authorization check
//        abort_unless($file->taskUser->user_id === auth()->id(), 403);
//
//        // Delete the file from storage
//        Storage::disk('public')->delete($file->file_path);
//
//        // Check if the file exists in the public storage
//        if (Storage::disk('public')->exists($file->file_path)) {
//            // Delete the file from storage
//            Storage::disk('public')->delete($file->file_path);
//        }
//
//        // Delete the file from the database
//        $file->delete();
//
//        // Return a success message
//        return response()->json(['success' => true, 'message' => 'فایل با موفقیت حذف شد']);
//    }
}
