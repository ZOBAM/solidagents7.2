<?php
namespace App\Classes;
use App\Models\{User, Property, House_detail, Land_detail, Property_image};
use Carbon\Carbon;
use Auth;

class UploadClass {
    private $target_file;
    private $save_dir;
    private $property;
    private $user_id = false;
    static $base_path;

    public function __construct($destination_dir,$id){
        $this->save_dir = $destination_dir;
        //if there is "profile" in the path it means it is dp otherwise property image
        if(strpos($destination_dir,'profile')){
            $this->user_id = $id;
        }
        else{
            $this->property_id = $id;
            $this->property = Property::findOrFail($this->property_id);
        }
        self::$base_path = (is_dir(public_path('../../public/images/')))? public_path('../../public/'):'' ;
    }
    static function get_base_path(){
        return (is_dir(public_path('../../public/images/')))? public_path('../../public/'):'' ;
    }
    public function upload($file){
        $this->target_file = $file;
        $imageName = $this->property->id.'_'.time().'_solidagents_'.$this->target_file->getClientOriginalName();
        $img_link = $this->save_dir.$imageName;
        //move file
        if($this->target_file->move(self::$base_path.$this->save_dir, $imageName)){
            if($this->user_id){
                $user = User::findOrFail($this->user_id);
                $user->dp_link = $img_link;
                $user->save();
            }
            else{
                $property_image = new Property_image;
                $property_image->property_id = $this->property->id;
                $property_image->link = $img_link;
                if($property_image->save()){
                    ++$this->property->image_count;
                    $this->property->save();
                };
            }
            return true;
        };
    }
    public function delete($file_obj, $file_type){
        if($file_type == "property_image"){
            $image_file = self::$base_path.$file_obj->getRawOriginal('link');
            if(file_exists($image_file)){
                unlink($image_file);
                Property_image::destroy($file_obj->id);
                --$this->property->image_count;
                $this->property->save();
                return true;
            }
            return false;
        }
        else{
            return "File type is not Property image.";
        }
    }
}
?>
