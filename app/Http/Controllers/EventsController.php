<?php

namespace App\Http\Controllers;

use App\DataTables\EventsDataTable;
use App\Http\Requests\StoreEventsRequest;
use App\Http\Requests\UpdateEventsRequest;
use App\Models\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(EventsDataTable $DataTable)
    {
        // return view('list');
        return $DataTable->render('list');
    }
    public function calendar(Request $request)
    {
        $events = Events::withCount('events')
            ->get();

        return view('calendar', compact('events'));
    }

    public function all_event(Request $request)
    {
        if ($request->ajax()) {
            $data = Events::whereBetween('event_start_date', [$request->start, $request->end])
                ->get(['id', 'event_title', 'event_start_date', 'event_end_date']);
            return response()->json($data);
        }
        return view('calendar');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEventsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventsRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'event_title' => 'required|max:30',
            'event_start_date' => 'required',
            'event_recurrence_type' => 'required',
        ]);

        $feed_back = array();
        if ($validator->passes()) {

            Events::updateOrCreate(['id' => $request->id], $request->all());

            $feed_back['type'] = 'alert-success';
            $feed_back['message'] = 'Saved Successfully';
            $feed_back['error'] = array();
        } else {
            $feed_back['type'] = 'alert-danger';
            $feed_back['error'] =  $validator->errors()->all();
        }

        return json_encode($feed_back);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Events  $events
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $events = Events::findOrFail($id);
        return $events;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Events  $events
     * @return \Illuminate\Http\Response
     */
    public function edit(Events $events)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEventsRequest  $request
     * @param  \App\Models\Events  $events
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventsRequest $request, Events $events)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Events  $events
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event_delete = Events::find($id)->delete();
        if ($event_delete) {
            $feed_back['type'] = 'alert-success';
            $feed_back['message'] = 'Deleted Successfully';
            $feed_back['error'] = array();
        }

        return json_encode($feed_back);
    }
}
