<?php

class Reservation extends \Eloquent {
	
	public static $rules = array(
	    "teacher_name" => "required",
	    "teacher_email" => "required|email",
	    "room_number" => "required",
	    "mods" => "required",
	    "date" => "required",
	    "cart_id" => "required"
	);
}