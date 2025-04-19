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

        $data_points = [];
        for ($date = strtotime($start_date); $date <= strtotime($end_date); $date += 86400) {
            $data_points[] = (object)[
                'label' => date('D, d M', $date),
                'date' => date('Y-m-d', $date),
            ];
        }

        $rooms = [
            (object)['id' => 1, 'name' => 'Room 101'],
            (object)['id' => 2, 'name' => 'Room 102'],
            (object)['id' => 3, 'name' => 'Room 103'],
        ];

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
            'admin' => rand(0, 1)
        ]);
    }

    public function addBooking(Request $request, $id = 0)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'room_number' => 'required|string|max:50',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'total_amount' => 'required|numeric|min:0',
        ]);

        if ($id) {
            $booking = Transaction::find($id);
            if (!$booking) {
                return response()->json(['success' => false, 'message' => 'Booking not found']);
            }

            $roomChanged = $booking->room_number !== $request->room_number;
            $dateChanged = $booking->check_in != $request->check_in || $booking->check_out != $request->check_out;

            if ($roomChanged || $dateChanged) {
                $conflict = Transaction::where('room_number', $request->room_number)
                ->where('id', '!=', $id)
                ->where(function ($q) use ($request) {
                    $q->whereBetween('check_in', [$request->check_in, $request->check_out])
                    ->orWhereBetween('check_out', [$request->check_in, $request->check_out])
                    ->orWhere(function ($q2) use ($request) {
                      $q2->where('check_in', '<', $request->check_in)
                      ->where('check_out', '>', $request->check_out);
                  });
                })->exists();

                if ($conflict) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The selected room is already booked for the chosen dates.',
                    ]);
                }
            }
        } else {
            $conflict = Transaction::where('room_number', $request->room_number)
            ->where(function ($q) use ($request) {
                $q->whereBetween('check_in', [$request->check_in, $request->check_out])
                ->orWhereBetween('check_out', [$request->check_in, $request->check_out])
                ->orWhere(function ($q2) use ($request) {
                  $q2->where('check_in', '<', $request->check_in)
                  ->where('check_out', '>', $request->check_out);
              });
            })->exists();

            if ($conflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected room is already booked for the chosen dates.',
                ]);
            }

            $booking = new Transaction();
        }

        $booking->customer_name = $request->customer_name;
        $booking->customer_email = $request->customer_email;
        $booking->room_number = $request->room_number;
        $booking->check_in = $request->check_in;
        $booking->check_out = $request->check_out;
        $booking->total_amount = $request->total_amount;
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => $id ? 'Booking updated successfully' : 'Booking created successfully',
            'data' => $booking,
        ]);
    }

    public function editBooking($rcd_id)
    {
        $booking_record = DB::table('transactions')->select('*')->where("id", $rcd_id)->first();

        $data["success"] = true;
        $data["booking_record"] = $booking_record;

        return response()->json($data);
    }

    public function deleteBooking($rcd_id)
    {
        $booking_record = DB::table('transactions')->where("id", $rcd_id)->first();

        if (!$booking_record) {
            $data["success"] = false;
            $data["message"] = "Not Found";
        } else {
            DB::table('transactions')->where("id", $rcd_id)->delete();
            $data["success"] = true;
            $data["message"] = "Delete successfully";
        }

        return response()->json($data);
    }
}
