<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use App\Models\TruckSubunit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;



class TruckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trucks = Truck::all();
        return view("trucks.index",[
           "trucks"=>$trucks
        ]);


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("trucks.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit' => 'required|max:255',
            'year' => 'required|integer|gte:1900|lte:' . (date('Y') + 5),
            'notes' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        Truck::create($request->all());
        return redirect()->route("trucks.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Truck $truck)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Truck $truck)
    {
        return view("trucks.edit",[
           "truck"=>$truck
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Truck $truck)
    {
        $validator = Validator::make($request->all(), [
            'unit' => 'required|max:255',
            'year' => 'required|integer|gte:1900|lte:' . (date('Y') + 5),
            'notes' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $truck->fill($request->all());
        $truck->save();
        return redirect()->route("trucks.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Truck $truck)
    {
        $truck->delete();
        return redirect()->route("trucks.index");
    }

    public function subunit(Truck $truck){
        $subunits = $truck->subunits;

        return view("trucks.subunit",[
            "truck"=>$truck,
            "trucks"=>Truck::all(),
            "subunits" => $subunits
        ]);
    }

    public function substitute(Truck $truck, Request $request)
    {
        // Retrieve the input data from the request

        $subunitTruckId = $request->subunit_truck_id;
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Validation: Check if the truck is being assigned as its own subunit
        if ($subunitTruckId == $truck->id) {
            return redirect()->back()->withErrors(['subunit_truck_id' => 'Truck cannot be assigned as its own subunit.']);
        }

        // Validation: Check for overlapping subunit dates
        $overlappingSubunits = TruckSubunit::where('main_truck_id', $truck->id)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $endDate)
                        ->where('end_date', '>=', $startDate);
                })
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '>=', $startDate)
                            ->where('start_date', '<=', $endDate);
                    })
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('end_date', '>=', $startDate)
                            ->where('end_date', '<=', $endDate);
                    });
            })
            ->get();

        if ($overlappingSubunits->isNotEmpty()) {
            return redirect()->back()->withErrors(['start_date' => 'Subunit dates cannot overlap with existing subunits.']);
        }

        // Validation: Check if the truck is already a subunit for another truck during the same period
        $overlappingMainTruck = TruckSubunit::where('subunit_truck_id', $truck->id)
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->get();

        if ($overlappingMainTruck->isNotEmpty()) {
            return redirect()->back()->withErrors(['start_date' => 'Truck is already a subunit for another truck during the specified period.']);
        }

        // Validation passed. Proceed with the rest of the code

        // Save the truck subunit
        $truckSubunit = new TruckSubunit();
        $truckSubunit->main_truck_id = $truck->id;
        $truckSubunit->subunit_truck_id = $subunitTruckId;
        $truckSubunit->start_date = $startDate;
        $truckSubunit->end_date = $endDate;
        $truckSubunit->save();

        return redirect()->route("trucks.index");
    }

    public function destroySub(Truck $truck, $truckSubunit): \Illuminate\Http\RedirectResponse
    {

        // Delete the specific truck_subunit associated with the truck
        $truck->subunits()->detach($truckSubunit);

        return redirect()->route("trucks.index");
    }


}
