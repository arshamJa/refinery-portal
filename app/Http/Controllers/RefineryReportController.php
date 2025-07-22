<?php
//
//namespace App\Http\Controllers;
//
//use App\Enums\MeetingStatus;
//use App\Enums\TaskStatus;
//use Illuminate\Support\Facades\Cache;
//use Illuminate\Support\Facades\DB;
//
//class RefineryReportController extends Controller
//{
//    public function index()
//    {
//        $percentages = Cache::remember('meeting_percentages', 3600, function () {
//            $counts = DB::table('meetings')
//                ->select('status', DB::raw('count(*) as count'))
//                ->groupBy('status')
//                ->pluck('count', 'status')
//                ->toArray();
//
//            $totalMeetings = array_sum($counts);
//
//            $percentages = [];
//            foreach (MeetingStatus::cases() as $status) {
//                $count = $counts[$status->value] ?? 0;
//                $percentages[$status->value] = $totalMeetings > 0 ? round(($count / $totalMeetings) * 100, 2) : 0;
//            }
//
//            return $percentages;
//        });
//
//        [$ja_year, $ja_month, $ja_day] = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
//        $now = sprintf('%04d/%02d/%02d', $ja_year, $ja_month, $ja_day);
//
//        $taskPercentages = Cache::remember('task_percentages', 3600, function () use ($now) {
//            $tasksAggregated = DB::table('task_users')
//                ->selectRaw("
//                SUM(CASE WHEN task_status = ? AND sent_date <= time_out THEN 1 ELSE 0 END) as completed_on_time,
//                SUM(CASE WHEN task_status = ? AND sent_date > time_out THEN 1 ELSE 0 END) as completed_with_delay,
//                SUM(CASE WHEN task_status = ? AND sent_date IS NULL AND time_out >= ? THEN 1 ELSE 0 END) as incomplete_on_time,
//                SUM(CASE WHEN task_status = ? AND sent_date IS NULL AND time_out <= ? THEN 1 ELSE 0 END) as incomplete_with_delay
//            ", [
//                    TaskStatus::SENT_TO_SCRIPTORIUM->value,
//                    TaskStatus::SENT_TO_SCRIPTORIUM->value,
//                    TaskStatus::PENDING->value,
//                    $now,
//                    TaskStatus::PENDING->value,
//                    $now,
//                ])
//                ->first();
//
//            $completedTasks = $tasksAggregated->completed_on_time;
//            $completedTasksWithDelay = $tasksAggregated->completed_with_delay;
//            $incompleteTasks = $tasksAggregated->incomplete_on_time;
//            $incompleteTasksWithDelay = $tasksAggregated->incomplete_with_delay;
//
//            $totalTasks = $completedTasks + $completedTasksWithDelay + $incompleteTasks + $incompleteTasksWithDelay;
//
//            return [
//                0 => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0,
//                1 => $totalTasks > 0 ? round(($completedTasksWithDelay / $totalTasks) * 100, 2) : 0,
//                2 => $totalTasks > 0 ? round(($incompleteTasks / $totalTasks) * 100, 2) : 0,
//                3 => $totalTasks > 0 ? round(($incompleteTasksWithDelay / $totalTasks) * 100, 2) : 0,
//            ];
//        });
//
//        return view('reportsTable.refinery-report', [
//            'percentages' => $percentages,
//            'taskPercentages' => $taskPercentages,
//        ]);
//    }
//
//
//}
