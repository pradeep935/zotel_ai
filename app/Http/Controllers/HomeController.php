<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Response;
 use App\Models\Transaction;

  use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Index')->withViewData(['sidebar' => 'dashboard']);
    }

  

public function dateWise(Request $request)
{
    $start_date = $request->date_start ?? date('Y-m-d');
    $end_date = $request->date_end ?? date('Y-m-d', strtotime('+7 days'));

    // Prepare date points
    $data_points = [];
    for ($date = strtotime($start_date); $date <= strtotime($end_date); $date += 86400) {
        $data_points[] = (object)[
            'label' => date('D, d M', $date),
            'date' => date('Y-m-d', $date),
        ];
    }

    // Define rooms (names must match those used in 'room_number' in DB)
    $rooms = [
        (object)['id' => 1, 'name' => 'Room 101'],
        (object)['id' => 2, 'name' => 'Room 102'],
        (object)['id' => 3, 'name' => 'Room 103'],
    ];

    // Get all transactions within the date range
    $transactions = Transaction::where(function ($query) use ($start_date, $end_date) {
        $query->whereBetween('check_in', [$start_date, $end_date])
              ->orWhereBetween('check_out', [$start_date, $end_date])
              ->orWhere(function ($q) use ($start_date, $end_date) {
                  $q->where('check_in', '<=', $start_date)
                    ->where('check_out', '>=', $end_date);
              });
    })->get();

    $room_list = [];

    foreach ($rooms as $room) {
        $records = [];

        foreach ($data_points as $dp) {
            $date = $dp->date;

            // Find booking for this room and date
            $booking = $transactions->first(function ($t) use ($room, $date) {
                return $t->room_number === $room->name &&
                       $t->check_in <= $date && $t->check_out >= $date;
            });

            if ($booking) {
                $records[] = (object)[
                'id' => $booking->id,
                'date' => $date,
                'customer_name' => $booking->customer_name,
                'customer_email' => $booking->customer_email,
                'total_amount' => $booking->total_amount,
                'created_at' => $booking->created_at,
                'check_in' => $booking->check_in,
                'check_out' => $booking->check_out,
                'room_number' => $booking->room_number,
                'color' => '#e0f7fa',
                'clientColor' => '#ffecb3',
                
                'remark' => null,
                'block_time' => false,
            ];

            }
        }

        $room->records = $records;
        $room_list[] = $room;
    }

    return response()->json([
        'success' => true,
        'data_points' => $data_points,
        'user_list' => $room_list,
        'admin' => rand(0, 1) // can be dynamic later
    ]);
}


// public function addBooking(Request $request) {
//     $validated = $request->validate([
//         'customer_name'   => 'required|string|max:255',
//         'customer_email'  => 'required|email|max:255',
//         'room_number'     => 'required|string|max:50',
//         'check_in'        => 'required|date|before:check_out',
//         'check_out'       => 'required|date|after:check_in',
//         'total_amount'    => 'required|numeric|min:0',
//     ]);

//     $conflict = Transaction::where('room_number', $validated['room_number'])
//         ->where(function ($query) use ($validated) {
//             $query->whereBetween('check_in', [$validated['check_in'], $validated['check_out']])
//                   ->orWhereBetween('check_out', [$validated['check_in'], $validated['check_out']])
//                   ->orWhere(function ($query2) use ($validated) {
//                       $query2->where('check_in', '<', $validated['check_in'])
//                              ->where('check_out', '>', $validated['check_out']);
//                   });
//         })
//         ->exists();

//     if ($conflict) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Room is already booked for the selected dates.',
//         ], 200);
//     }

//     $transaction = new Transaction();
//     $transaction->fill($validated);
//     $transaction->save();

//     return response()->json([
//         'success' => true,
//         'message' => 'Booking successfully done.',
//     ]);
// }

public function addBooking(Request $request,$id=0) {
    if(!$id){
        $validated = $request->validate([
            'id'             => 'required|integer|exists:transactions,id',
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'room_number'    => 'required|string|max:50',
            'check_in'       => 'required|date|after_or_equal:today',
            'check_out'      => 'required|date|after:check_in',
            'total_amount'   => 'required|numeric|min:0',
        ]);

        $booking = Transaction::where('id', $validated['id'])->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found.',
            ]);
        }

        $roomChanged = $booking->room_number !== $validated['room_number'];
        $dateChanged = $booking->check_in->toDateString() !== $validated['check_in'] ||
                       $booking->check_out->toDateString() !== $validated['check_out'];

        if ($roomChanged || $dateChanged) {
            $conflict = Transaction::where('room_number', $validated['room_number'])
                ->where('id', '!=', $validated['id'])
                ->where(function ($q) use ($validated) {
                    $q->whereBetween('check_in', [$validated['check_in'], $validated['check_out']])
                      ->orWhereBetween('check_out', [$validated['check_in'], $validated['check_out']])
                      ->orWhere(function ($q2) use ($validated) {
                          $q2->where('check_in', '<', $validated['check_in'])
                             ->where('check_out', '>', $validated['check_out']);
                      });
                })
                ->exists();

            if ($conflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'Room is already booked for the selected dates.',
                ]);
            }
        }

        $booking->update($validated);

        return response()->json([
            'success' => true,
            'message' => $roomChanged
                ? 'Booking updated. Room was successfully changed.'
                : 'Booking updated successfully.',
        ]);
    } else{
        dd($request->all());
        DB::table('transactions')->insert([
            "check_in"=>$request->change_date_from,
        ]);

    }
}


    public function editBooking($rcd_id){
        $booking_record = DB::table('transactions')->select('*')->where("id",$rcd_id)->first();

        $data["success"] = true;
        $data["booking_record"] = $booking_record;

        return response()->json($data);
    }

    public function deleteBooking($rcd_id){
        $booking_record = DB::table('transactions')->where("id",$rcd_id)->first();

        if(!$booking_record){
            $data["success"] = false;
            $data["message"] = "Not Found";
        }else{
            DB::table('transactions')->where("id",$rcd_id)->delete(); 

            $data["success"] = true;
            $data["message"] = "Delete successfully";
        }

        return response()->json($data);
    }

}

