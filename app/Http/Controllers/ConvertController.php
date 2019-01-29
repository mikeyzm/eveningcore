<?php

namespace App\Http\Controllers;

use App\Convert;
use App\ConvertOption;
use App\Enums\ConvertStatus;
use FFMpeg\Format\Audio\Mp3;
use Illuminate\Http\Request;

class ConvertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $converts = Convert::latest()->take(5)->get();
        return view('home', compact('converts'));
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $source = $request->file('source');
        // create covnert record
        $convert = Convert::create([
            'status' => ConvertStatus::Pending,
            'original_name' => $source->getClientOriginalName(),
            // generate random filename
            'file_name' => str_random(32) . '.' . $source->getClientOriginalExtension(),
        ]);
        // save options
        $convert->options()->saveMany(collect($request->options)->map(function ($value, $name) {
            return new ConvertOption(compact('value', 'name'));
        }));
        // store audio to temp folder
        $source->storeAs('temp', $convert->file_name);
        // start convert progress
        \FFMpeg::open('temp/' . $convert->file_name)
            ->addFilter(
                '-af',
                implode(',', [
                    // set rubberband
                    'rubberband=tempo=' . $convert->getOption('tempo') . ':pitch=' . $convert->getOption('pitch'),
                    // set volume
                    'volume=' . $convert->getOption('volume')
                ])
            )
            // export result
            ->export()
            ->toDisk('public')
            ->informat(new Mp3())
            ->save('converts/' . $convert->file_name);
        // back to submit page
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Convert $convert
     * @return \Illuminate\Http\Response
     */
    public function show(Convert $convert)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Convert $convert
     * @return \Illuminate\Http\Response
     */
    public function edit(Convert $convert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Convert $convert
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Convert $convert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Convert $convert
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convert $convert)
    {
        //
    }
}