<?php

class ReservationController extends \BaseController {

	/**
	 * Display the specified resource.
	 * GET /reservation/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return Response::json(array("status" => "ok", "reservation" => Reservation::find($id)));
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /reservation/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /reservation/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /reservation/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}