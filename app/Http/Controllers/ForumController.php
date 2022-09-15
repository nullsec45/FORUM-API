<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\{ForumResource, ForumsResource};
use App\Http\Controllers\{AuthUserTrait};
use Illuminate\Support\Facades\Validator;


class ForumController extends Controller
{
    use AuthUserTrait;

    public function __construct()
    {
        return auth()->shouldUse("api");
    }
   
    public function index()
    {
        return ForumsResource::collection(Forum::with("user")
                                                 ->withCount("comments")
                                                 ->paginate(3)
        );
    }

    
    public function store(Request $request)
    {
        $this->validateRequest($request);
        $user=$this->getAuthUser();        
        
        // Untuk mengetahui user yang sedang login   
        $user->forums()->create(["title" => $request->title,
                                "body" => $request->body,
                                "slug" => Str::slug($request->title,"-"),
                                "category" => $request->category
                            ]);
        return response()->json(["message" => "Successfully Posted"]);
    }

    public function show($id)
    {
        return new ForumResource(
            Forum::with("user","comments")->find($id)
        );
    }

    public function update(Request $request, $id)
    {
        $this->validateRequest($request);
        // $user=$this->getAuthUser();        
        $forum=Forum::find($id);

        // check ownership (authorized)
        $this->checkOwnership($forum->user_id);

        Forum::find($id)->update([
                                "title" => $request->title,
                                "body" => $request->body,
                                "slug" => Str::slug($request->title,"-"),
                                "category" => $request->category
                            ]);

        return response()->json(["message" => "Successfully Updated"]);
    }

    public function destroy($id)
    {
        $forum=Forum::find($id);
            
        // check ownership (authorized)
        $this->checkOwnership($forum->user_id);

        $forum->delete();

        return response()->json(["message" => "Succesfully deleted"]);
    }

    public function category($category){
        return ForumResource::collection(
            Forum::with("user")->where("category", $category)->paginate(10)
        );
    }

    private function validateRequest($request){
        $validator=Validator::make($request->all(),  [
            "title" => "required|min:5",
            "body" => "required|min:10",
            "category" => "required"
        ]);
       
       if($validator->fails()){
         response()->json($validator->messages(), 422)->send();
         exit;
       }
    }
}
