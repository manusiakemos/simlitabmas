<?php

namespace App\Http\Controllers;

use App\Http\Resources\WidgetResource;
use App\Models\Penelitian;
use App\Models\SurveyStatus;
use App\Notifications\Pemberitahuan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;

class DashboardController extends Controller
{
    public function chart(Request $request)
    {
        $ss = SurveyStatus::findByLevel(2)->first();
        $currentYear = date('Y');
        for ($i = 0; $i < 3; $i++) {
            $years[] = $currentYear - $i;
        }
        $data = Penelitian::selectRaw('count(*) as value, penelitian_tahun_pelaksanaan as label')
            ->groupBy('penelitian_tahun_pelaksanaan')
            ->whereIn('penelitian_tahun_pelaksanaan', $years)
            ->where('ss_id', $ss->ss_id)
            ->get();
        return response([
            'title' => "Grafik Jumlah Penelitian " . Arr::first($years) . "-" . Arr::last($years),
            'data' => $data
        ]);
    }

    public function widget(Request $request, $type = 'penelitian')
    {
        $total = Penelitian::count();
//            where('penelitian_tahun_pelaksanaan', date('Y'))
//            ->orWhereNull('penelitian_tahun_pelaksanaan', date('Y'))
//            ->count();

        session()->put('total', $total);

        $data = Penelitian::selectRaw('count(*) as value, ss_value as label, ss_level as level')
            ->join('status', 'status.ss_id', '=', 'penelitian.ss_id')
//            ->where('penelitian_tahun_pelaksanaan', date('Y'))
//            ->orWhereNull('penelitian_tahun_pelaksanaan', date('Y'))
            ->groupBy('penelitian.ss_id');
        if(isset($type) && $type == 'pengabdian'){
            $data = $data->where('is_pengabdian', true);
        }
        return WidgetResource::collection($data->get());
    }

    public function broadcast()
    {
        $users = User::all();
        Notification::send($users, new Pemberitahuan('pengabdian', 'pengabdian'));
        Notification::send($users, new Pemberitahuan('penelitian', 'penelitian'));
    }
}
