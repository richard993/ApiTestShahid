<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InteractionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $interactions = Interaction::get();
        return response()->json([ 'status' => 'success',
        'data'   => $interactions]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name'          => 'required|unique:interactions,name',
                'description'   => 'required',
            ]);

            // Your logic for handling the validated data
        } catch (ValidationException $e) {
            // Validation failed. Return JSON response with custom error messages.

            $errors = $e->validator->errors();

            $nameError = $errors->first('name');
            $descError = $errors->first('description');

            return response()->json(['message' => 'Validation failed', 'errors' => [
                'name'    => $nameError ? 'The name field is required.' : null,
                'description' => $descError ? 'The description field is required.' : null,
            ]], 422);
        }

        if (Interaction::where('name', $request->name)->first()) {
            return response([
                'message' => 'Interaction already exists',
                'status' => 'failed'
            ], 200);
        }

        $interaction = Interaction::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return response([
            'message' => 'Interaction Saved Successfully',
            'status' => 'success',
            'data'   => $interaction
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Interaction  $interaction
     * @return \Illuminate\Http\Response
     */
    public function show(Interaction $interaction)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Interaction  $interaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Interaction $interaction)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Interaction  $interaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name'          => 'required|unique:interactions,name',
                'description'   => 'required',
            ]);

            // Your logic for handling the validated data
        } catch (ValidationException $e) {
            // Validation failed. Return JSON response with custom error messages.

            $errors = $e->validator->errors();

            $nameError = $errors->first('name');
            $descError = $errors->first('description');

            return response()->json(['message' => 'Validation failed', 'errors' => [
                'name'    => $nameError ? 'The name field is required.' : null,
                'description' => $descError ? 'The description field is required.' : null,
            ]], 422);
        }

        $interaction = Interaction::where('id', $id)->first();
        $interaction->name = $request->name;
        $interaction->description = $request->description; 
        $interaction->save();

       
        return response([
            'message' => 'Interaction Updated Successfully',
            'status' => 'success',
            'data'   => $interaction
        ], 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Interaction  $interaction
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $interaction = Interaction::where('id', $id)->first();
        $interaction->delete();

       
        return response([
            'message' => 'Interaction deleted Successfully',
            'status' => 'success',
            'data'   => $interaction
        ], 201);
    }
}
