<?php

namespace App\Http\Controllers;

use App\Models\{Forum, ForumComment};
use Illuminate\Http\Request;
use App\Http\Controllers\AuthUserTrait;
use Illuminate\Support\Facades\Validator;



class ForumCommentController extends Controller
{
    use AuthUserTrait;

    public function __construct()
    {
        return auth()->shouldUse("api");
    }

    public function store(Request $request, $forumId)
    {
        $this->validateRequest($request);
        $user=$this->getAuthUser();        
        
        // Untuk mengirim data berdasarkan user yang sedang login   
        $user->forumComments()->create(["body" => $request->body, 
                                        "forum_id" => $forumId]);

        return response()->json(["message" => "Successfully Comment Posted"]);
    }

  
    public function show($id)
    {
        return Forum::with("user:id,username","comments.user:id,username")->find($id);
    }

    public function update(Request $request, $forumId, $commentId)
    {
        $this->validateRequest($request);
        $forumComment=ForumComment::find($commentId);

        $this->checkOwnership($forumComment->user_id);

        $forumComment->update([
            "body" => $request->body,
        ]);

        return response()->json(["message" => "Successfully comment updated"]);
    }

  
    public function destroy($forumId, $commentId)
    {
        $forumComment=ForumComment::find($commentId);
        $this->checkOwnership($forumComment->user_id);
        
        $forumComment->delete();

        return response()->json(["Message" => "Succesfully comment deleted"]);
    }

     private function validateRequest($request){
        $validator=Validator::make($request->all(),  [
            "body" => "required|min:10",
        ]);
       
       if($validator->fails()){
         return response()->json($validator->messages(), 422)->send();
         exit;
       }
    }

}
