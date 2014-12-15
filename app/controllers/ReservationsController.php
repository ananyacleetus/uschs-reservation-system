<?php

class ReservationsController extends BaseController {
    
    /**
	 * Filter reservations based on date
	 * GET /reservations/filter_by_date
	 * 
	 * @return Response
	 */ 
	public function filterByDate()
	{
		$date = Input::get("date");
		$date = new DateTime($date);

		$reservations = Reservation::where("date", "=", $date->format("m/d/y"))->get()->toArray();
		
		if (count($reservations) > 0) {
			for ($i = 0; $i < count($reservations); $i++) {
				$cart = Cart::find($reservations[$i]["cart_id"]);
				$reservations[$i]["cart_name"] = $cart->cart_name;
			}
		}

		return Response::json(array("status" => "ok", "reservations" => $reservations));
	}
	
	/**
	 * Store a newly created resource in storage.
	 * POST /reservations
	 *
	 * @return Response
	 */
	public function create()
	{
		$validator = Validator::make($data = Input::all(), Reservation::$rules);
		
		if ($validator->fails()) {
			return Response::json(array("status" => "error", "errors" => $validator->messages()));
		}
		
		// Type conversion
		$data["cart_id"] = (int)$data["cart_id"];
		
		$reservation = new Reservation();
		$reservation->teacher_name = $data["teacher_name"];
		$reservation->teacher_email = $data["teacher_email"];
		$reservation->room_number = $data["room_number"];
		$reservation->mods = implode(",", $data["mods"]);
		
		$date = new DateTime($data["date"]);
		$reservation->date = $date->format("m/d/y");
		
		$reservation->cart_id = $data["cart_id"];
		$reservation->save();
		
		return Response::json(array("status" => "ok", "reservation" => $reservation->toArray()));
		
		$cart = Cart::find($reservation->cart_id);
		
		//find rooms before each mod
		$previousRoomForMod = array();
		foreach ($reservation->mods as &$mod) {
			$previousRoom = Reservation::where('date', '=', $reservation->date->format("m/d/y"), 'and')->where('"," + mod + ","','LIKE', "$mod")->pluck('room_number');
			$previousRoomForMod[$mod] = $previousRoom; 
		}
		
		//Make a mail
		Mail::send('emails.alert.reservation', array('date' => $reservation->date, 'cart_name' => $cart, 'teacher_name' => $reservation->teacher_name, 'previous_room_by_mod' => $previousRoomForMod, 'mods' => $reservation->mods), function($message)
		{
    		$message->to($reservation->teacher_email, 'Upper St. Clair High School Reservation System')
    			->subject('You made a reservation with cart '.$cart->cart_name);
		});
	}
}