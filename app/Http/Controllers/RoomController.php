<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Status;

class RoomController extends Controller
{
    public function read()
    {
        $rooms = Room::all();
        $paginatedRooms = Room::paginate(6);
        
        return view('room.read', compact('rooms', 'paginatedRooms'));
    }

    public function create()
    {
        $statuses = Status::all();
        $rooms = Room::all();
        $paginatedRooms = Room::paginate(5);
        return view('room.create', compact('rooms', 'paginatedRooms', 'statuses'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'roomname' => 'required|string',
            'roomprice' => 'required|integer',
            'roomcapacity' => 'required|integer',
            'roomdescription' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5000|nullable', 
            'status_id' => 'required|integer',
        ]);


        if ($request->hasFile('images')) {
            $imageUrls = [];
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('room_images', 'public');
                $imageUrls[] = asset('storage/' . $imagePath);
            }
            $validatedData['images'] = json_encode($imageUrls); // Convert array to JSON
        }

        Room::create($validatedData); // Saving the data

        return redirect(route('room.read'))->with('success', 'Successfully Save');
    }

    public function edit(Room $room)
    {
        $statuses = Status::all();
        $rooms = Room::all();
        $paginatedRooms = Room::paginate(6);
        return view('room.edit', compact('rooms', 'paginatedRooms', 'statuses'), ['room' => $room]);
    }

    public function update(Request $request, Room $room)
    {
        $validatedData = $request->validate([
            'roomname' => 'required|string',
            'roomprice' => 'required|integer',
            'roomcapacity' => 'required|integer',
            'roomdescription' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
            'status_id' => 'required|integer',
        ]);

        if ($request->hasFile('images')) {
            $imageUrls = [];
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('room_images', 'public');
                $imageUrls[] = asset('storage/' . $imagePath);
            }
            $validatedData['images'] = json_encode($imageUrls); // Convert array to JSON
        }

        $room->update($validatedData);

        return redirect(route('room.read'))->with('success', 'Updated Successfully');
    }

    public function destroy(Room $room) {

        $room->delete();
        return redirect(route('room.read'))->with('success', 'Successfully Deleted');

    }

    public function archives(){
        $rooms = Room::onlyTrashed()
            ->orderBy('id', 'desc')->get();
        $paginatedRooms = Room::paginate(6);

        return view('room.archives', ['paginatedRooms' => $paginatedRooms, 'rooms' => $rooms]);

    }

    public function restore(Room $room, Request $request)
    {
        $room->restore();

        return redirect(route('room.archives'))->with('success', 'Successfully Restored');
    }

}
