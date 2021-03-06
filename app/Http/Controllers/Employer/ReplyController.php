<?php

namespace App\Http\Controllers\Employer;

use App\Events\Employer\ReviewReplied;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\WorkReviews;
use App\Model\WorkReviewReply;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class ReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $employer = JWTAuth::parseToken()->authenticate();
        if (!isset($request->review_id)) {
            return response()->json(['status' => 0,'msg' => '缺少参数review_id']);
        }
        if (!$review = WorkReviews::find($request->review_id)) {
            return response()->json(['status' => 0,'msg' => '找不到对应的评价']);
        }
        if ($result = DB::table('work_review_reply')->where('review_id',$review->id)->where('employer_id',$employer->id)->first()) {
            return response()->json(['status' => 0,'msg' => '你已经回复了该评价，不能再次回复']);
        }
        if (!isset($request->text)) {
            return response()->json(['status' => 0,'msg' => '缺少参数text']);
        }
        $reply = new WorkReviewReply();
        $reply->employer_id = $employer->id;
        $reply->review_id = $review->id;
        $reply->content = $request->text;
        $reply->save();
        event(new ReviewReplied($reply));
        return response()->json(['status' => 1,'msg' => '回复成功','reply' => $reply]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
