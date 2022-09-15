<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ForumComment;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function show($username){
        return new UserResource(
                    User::where("username", $username)
                         ->select("id","username","created_at")
                         ->first()
        );
        
    }
    public function activity($username){
        return new UserResource(
                     User::where("username", $username)
                         ->with("forums", "forumComments")
                         ->first()
        );
    }
}
