<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainController extends Controller
{
    public function show(){
        $condition = [
            'date'      => date('Y-m-d'),
            'status'    => true,
        ];
        $allTickets = Ticket::where($condition)->get();
        $seatsAvailable = 80 - count($allTickets);
        return view('ticket-book-form',compact('seatsAvailable'));
    }

    public function book(Request $request){

        $condition = [
            'date'      => date('Y-m-d'),
            'status'    => true,
        ];
        $allTickets = Ticket::where($condition)->get();
        $allTickets = array_column($allTickets->toArray(),'seat_number');
        sort($allTickets);
        // print_r($allTickets);
        // echo '<br>';
        $seatsAvailable = 80 - count($allTickets);

        // use laravel validation instead below code
        $max = $seatsAvailable < 7 ? $seatsAvailable : 7;
        $rule = "required|integer|min:1|max:".$max;
        $validated = $request->validate([
            'no_of_tickets' => $rule,
        ]);

        $bookTickets = $request->no_of_tickets;
        
        $rows = 12;
        $columns = 7;
        $group = [];
        $seats = [];
        $outerBreak = false;
        $seatNumber = 1;
        $endOfLoop = false;
        for ($i=1; $i <= $rows; $i++) { 
            for ($j=1; $j <= $columns; $j++) {
                // break the loop for extra seats that are not present
                if($i == 12 && $j > 3){
                    $endOfLoop = true;
                    $outerBreak = true;
                    break;
                }
                // check if seats are there in same row & 
                // store in group so that we can use later if seats are not in same row
                if(!in_array($seatNumber,$allTickets)){
                    $group[$i][$j] = $seatNumber;
                }
                $seatNumber++;
            }
        }

        $groupCount = [];
        foreach ($group as $key => $value) {
            $groupCount[$key] = count($value);
        }
        asort($groupCount);

        // exact number of seats available in that row 
        $ticketBooked = false;
        foreach ($groupCount as $key => $value) {
            if($bookTickets == $value){

                $seats = $group[$key];
                // book tickets
                $dataToBeInserted = $this->insertData($seats);
                $inserted = Ticket::insert($dataToBeInserted);
                $ticketBooked = true;
                break;
            }else{
                // less number of seats available in that row
                if($bookTickets < $value){
                    foreach ($group[$key] as $seat) {
                        $seats[] = $seat;
                        if(count($seats) == $bookTickets){
                            $outerBreak = true;
                            $ticketBooked = true;
                            $dataToBeInserted = $this->insertData($seats);
                            $inserted = Ticket::insert($dataToBeInserted);
                            break;
                        }
                    }
                    if($outerBreak){
                        break;
                    }
                    
                }

            }
        }


        // seats available in different rows
        if(!$ticketBooked){
            foreach ($group as $value) {
                foreach ($value as $seat) {
                    $seats[] = $seat;
                    if(count($seats) == $bookTickets){
                        $outerBreak = true;
                        $dataToBeInserted = $this->insertData($seats);
                        $inserted = Ticket::insert($dataToBeInserted);
                        break;
                    }
                }
                if($outerBreak){
                    break;
                }
            }
            
        }

        $allTickets = array_merge($allTickets,$seats);
        return view('booked-tickets',compact('allTickets','rows','columns','seats'));
    }

    private function insertData($seats){
        $data = [];
        foreach ($seats as $key => $value) {
            $data[] = [
                'user_id'       => Auth::id() ?? 1,
                'seat_number'   => $value,
                'date'          => date('Y-m-d')
            ];
        }
        return $data;
    }

    public function delete(Request $request){
        Ticket::truncate();
        return redirect(route('ticket-book'));
    }
}
