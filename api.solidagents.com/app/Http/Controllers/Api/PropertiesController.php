<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\{UploadClass};
use App\Models\{Property, House_detail, Land_detail, Like, Property_image, User};
use Illuminate\Support\Facades\Log;

class PropertiesController extends Controller
{
    public function append_resources($property_obj, $type = "images")
    {
        if ($type == "images" && $property_obj->image_count > 0) {
            //Log::info($property_obj->property_image);
            return $property_obj->property_image;
        }
        if ($type == "details") {
            $property_model = "App\\Models\\" . ucfirst($property_obj->type) . "_detail";
            $type = $property_obj->type;
            $property_obj->$type = $property_model::where('property_id', $property_obj->id)->first();
            return true;
        }
    }
    public function index(Request $request, $type = false, $id = false, $user_id = false)
    {
        $get_specific_prop = false;
        //Property::where('price', '>', 0)->searchable();
        //update all user property count since the column was added new.
        /* $users = User::get();
        foreach ($users as $user) {
            $user->property_count = Property::where('user_id', $user->id)->count();
            $user->save();
        } */
        if ($type) {
            if (is_numeric($type)) { //properties/2 ::fetching properties of user id 2
                $properties = Property::where('user_id', $type)->orderBy('created_at', 'DESC')->get();
            } else {
                if (is_numeric($id)) { //properties/land/13
                    $properties = Property::where('type', $type)->where('id', $id)->first();
                    $get_specific_prop = true;
                    //update likes
                    $properties->views++;
                    $properties->save();
                    //get liked if user_id is set
                    if ($user_id) {
                        $liked = Like::where('property_id', $id)->where('user_id', $user_id)->first();
                        if ($liked) {
                            $properties->liked = true;
                        } else {
                            $properties->liked = false;
                        }
                    }
                } else //properties/land
                    $properties = Property::where('type', $type)->orderBy('created_at', 'DESC')->get();
            }
        } else {
            $properties = Property::take(10)->orderBy('created_at', 'DESC')->get();
        }
        if ($get_specific_prop || ($get_specific_prop == false && count($properties) > 0)) {
            if (isset($properties[0])) { //only loop for arrays
                foreach ($properties as $property) {
                    $property->property_image = $this->append_resources($property);
                    $this->append_resources($property, 'details');
                }
            } else { //request that returns only one specific property
                if ($properties) {
                    //if($properties->image_count>0)
                    $properties->property_image = $this->append_resources($properties);
                    $properties->owner = $properties->user;
                    $properties->owner->dp_link = URL($properties->owner->dp_link);
                    $this->append_resources($properties, 'details');
                }
            }
        }
        return $properties;
        //return $properties[0]->property_image[0]->getRawOriginal('link');
    }
    public function store(Request $request)
    {
        $response = [];
        $this->validate($request, [
            'type'    => 'required|string|min:2|max:12',
            'title'     => 'required|string|min:15|max:55',
            'dealType'           =>  'required|string|min:5|max:15',
            'price'         => 'required|numeric|min:1000|max:10000000000',
            'status'      => 'required|string|min:5|max:19',
            'state'      => 'required|string|min:3|max:15',
            'lga'      => 'required|string|min:3|max:15',
            'town'      => 'required|string|min:3|max:15',
            'desc'      => 'string|min:3|max:255',
            'purpose'      => 'required|string|min:3|max:15',
            'brokerFee'      => 'required|numeric|min:0|max:1',
        ]);
        $isEditing = $request->has('editedPropID');
        $deleted_images = $request->has('deletedImages');
        /* if($isEditing)
        return ['msg' => "Deleted imgs(".count($request->deletediImages)."): $request->editedPropID"];
        else
        return ['msg' => "No deleted imgs"]; */
        $property = $isEditing ? Property::findOrFail($request->editedPropID) : new Property;
        $property->user_id = $request->user()->id;
        $property->type = $request->type;
        $property->title = $request->title;
        $property->deal = $request->dealType;
        $property->price = $request->price;
        $property->status = $request->status;
        $property->state = $request->state;
        $property->lga = $request->lga;
        $property->town = $request->town;
        $property->desc = $request->desc;
        $property->purpose = $request->purpose;
        $property->broker_fee = $request->brokerFee;
        if (!$isEditing) {
            $request->user()->property_count++;
            $request->user()->save();
        }

        if ($request->has('type') && $request->type == "land") {
            $this->validate($request, [
                //for land
                'plots'             => 'required|numeric|min:1|max:1000',
                'size'              => 'required|numeric|min:50|max:1000000',
                'unit'              =>  'required|string|min:3|max:8',
                'allocationType'    => 'required|string|min:5|max:55',
            ]);
            $land = $isEditing ? Land_detail::where('property_id', $request->editedPropID)->first() : new Land_detail;
            $property->save();
            $land->plots            = $request->plots;
            $land->size             = $request->size . '_' . $request->unit;
            $land->allocation_type  = $request->allocationType;
            $land->property_id      = $property->id;
            $land->save();
        }
        if ($request->has('type') && $request->type == "house") {
            $this->validate($request, [
                //for house
                'totalRooms'        => 'required|numeric|min:0|max:1000',
                'bedrooms'          => 'nullable|numeric|min:0|max:100',
                'bathrooms'         => 'nullable|numeric|min:0|max:100',
                'parkingSpace'      => 'required|numeric|min:0|max:1',
                'parkingSpaceSize'  => 'nullable|numeric|min:1|max:200',
                'firstResident'     => 'required|numeric|min:0|max:1',
                'furnishing'        => 'nullable|string|min:5|max:25',
                'housingQuality'    => 'nullable|string|min:5|max:55',
                'smoking'           => 'nullable|numeric|min:0|max:1',
                'pets'              => 'nullable|numeric|min:0|max:1',
                'parties'           => 'nullable|numeric|min:0|max:1',
                'minimumRent'       => 'nullable|numeric|min:1000|max:10000000',
                'water'             => 'required|string|min:4|max:31',
                'light'             => 'required|numeric|min:0|max:1',
                'fenced'            => 'required|string|min:2|max:55',
                'facilities'        => 'nullable|string|min:5|max:55',
                'guestCapacity'     => 'nullable|numeric|min:1|max:50000',
            ]);
            $house = $isEditing ? House_detail::where('property_id', $request->editedPropID)->first() : new House_detail;
            $property->save();
            $house->property_id         = $property->id;
            $house->total_rooms         = $request->totalRooms;
            $house->bedrooms            = $request->bedrooms;
            $house->bathrooms           = $request->bathrooms;
            $house->parking_space       = $request->parkingSpace;
            $house->parking_space_size  = $request->parkingSpaceSize;
            $house->first_resident      = $request->firstResident;
            $house->furnishing          = $request->furnishing;
            $house->housing_quality     = $request->housingQuality;
            $house->smoking             = $request->smoking;
            $house->pets                = $request->pets;
            $house->parties             = $request->parties;
            $house->minimum_rent        = $request->minimumRent;
            $house->water               = $request->water;
            $house->light               = $request->light;
            $house->fenced              = $request->fenced;
            $house->facilities          = $request->facilities;
            $house->guest_capacity      = $request->guestCapacity;
            $house->save();
        }

        if ($request->hasFile('images.*')) {
            $upload = new UploadClass('images/' . $property->type . '_images/', $property->id);
            foreach (request()->images as $image) {
                $upload->upload($image);
                //$image = $request->file('image');//for single image file
                /* $imageName = time().'_solidagents_'.$image->getClientOriginalName().'.'.$image->getClientOriginalExtension();
                $move_path = (is_dir(public_path('../../public/images/')))? public_path('../../public/images/land_images/'):'images/land_images/' ;
                $image->move($move_path, $imageName); */
            }
            $response['images_found'] = $request->all();
        }
        if ($deleted_images) { // if the array of deleted img ids were posted
            //delete links from db and files from server
            $upload_class = new UploadClass('images/' . $property->type . '_images/', $property->id);
            $dImg = $request->deletedImages;
            foreach ($dImg  as $id) {
                $property_image = Property_image::findOrFail($id);
                //$response = $upload_class->delete($property_image,"property_image");
                $upload_class->delete($property_image, "property_image");
            }
            $response['image_del_edit'] = "Image(s) deleted";
        } else
            return ['plots_not_found' => $response];
    }
    public function delete($id)
    {
        $property = Property::findOrFail($id);
        $upload_class = new UploadClass('images/' . $property->type . '_images/', $property->id);
        $property_images = Property_image::where('property_id', $id)->get();
        $response = "About to delete prop of ID: '.$id";
        if (Property::destroy($property->id)) {
            $response = "Property deleted";
            if ($property->image_count > 0) {
                if (count($property_images) > 0) {
                    $response = "Found img in db; ";
                    //$public_path = (is_dir(public_path('../../public/')))? '../public/':'';
                    $public_path = UploadClass::get_base_path();
                    foreach ($property_images as $image) {
                        if ($upload_class->delete($image, "property_image")) {
                            $response =  "Property deleted alongside associated images.";
                        } else
                            $response =  "Property deleted images can't delete.";
                        /* $image_file = $public_path.$image->getRawOriginal('link');
                       if(file_exists($image_file)){
                            unlink($image_file);
                            $response = "Property deleted alongside associated images.";
                        } */
                    }
                }
            }
        }
        return ['message' => $response];
    }
}
