<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Listing;
use Auth;

class ListingController extends Controller
{
    public function __construct(Listing $list){
        $this->list = $list;
    }

    /**
     * Get listings
     *
     * @return [array] listing objects
     */
    public function index(Request $request)
    {
    	if($request->ajax()) {

            $lists = $this->list->with('submitter')->get();

            return datatables()->of($lists)
                ->editColumn('submitter.name', function($list){
                    return optional($list->submitter)->name;
                })
                ->editColumn('action', function ($list) {
                    $button = "";

                    $button .= '<a onclick="edit('.$list->id.')" href="javascript:;" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a> ';

                    $button .= '<a onclick="remove('.$list->id.')" href="javascript:;" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>';

                    return $button;
                })
                ->make(true);
        }

        return view('listing.index');
    }


    /**
     * Get listing
     *
     * @return [json] listing object
     */
    public function edit(Listing $list)
    {
    	return view('listing.edit',compact('list'));
    }

    /**
     * Create listing
     *
     * @param  [string] list_name
     * @param  [string] address
     * @param  [string] latitude
     * @param  [string] longitude
     * @return [integer] listing id
     */
    public function store(Request $request)
    {
    	// Validation
    	$validator = \Validator::make($request->all(), [
            'list_name' => 'required|string|max:80',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'title' => 'Failed!', 
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ]);
        }

        $list = $this->list->create([
            'list_name' => $request->list_name,
            'address' => $request->address,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'submitter_id' => auth()->user()->id
        ]);

        return response()->json(['status' => 'success', 'title' => 'Success!', 'message' => 'Data has been saved.']);
    }

    /**
     * Update listing
     *
     * @return [integer] listing id
     */
    public function update(Request $request, Listing $list)
    {
    	// Validation
    	$validator = \Validator::make($request->all(), [
            'list_name' => 'sometimes|required|string|max:80',
            'address' => 'sometimes|required|string|max:255',
            'latitude' => 'sometimes|required|numeric',
            'longitude' => 'sometimes|required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
            	'errors' => $validator->errors(),
            	'status' => [
	            	'code' => 422,
	            	'message' => 'Validation failed'
	            ]
            ]);
        }
        
    	$update = $list->update($request->all());

    	return response()->json(['status' => 'success', 'title' => 'Success!', 'message' => 'Data has been updated.']);
    }

    /**
     * Delete listing
     *
     * @return [string] status
     */
    public function delete(Listing $list)
    {
        $list->delete();
        
        return response()->json(['status' => 'success', 'title' => 'Success!', 'message' => 'Data has been deleted.']);
    }

    /**
     * Listig by user id
     *
     * @return [array] listing objects
     * @return [json] status
     */
    public function showByEmployeeId(Request $request)
    {
        // Validation
        $validator = \Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => [
                    'code' => 422,
                    'message' => 'Validation failed'
                ]
            ]);
        }

        $lists = $this->list->calculateDistance($request->latitude,$request->longitude)->where('submitter_id', Auth::user()->id)->get();
        
        return response()->json([
            'listing' => $lists,
            'status' => [
                'code' => 200,
                'message' => 'Listing successfully retrieved'
            ]
        ]);
    }
}
