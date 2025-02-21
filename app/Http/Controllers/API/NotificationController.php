<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Notification;
use Validator;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\JsonResponse;
   
class NotificationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        $notifications = Notification::all();
    
        return $this->sendResponse(NotificationResource::collection($notifications), 'Notifications retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'receptor_id' => 'required',
            'author_name' => 'required',
            'message' => 'required',
            'type' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $notification = Notification::create($input);
   
        return $this->sendResponse(new NotificationResource($notification), 'Notification created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        $notification = Notification::find($id);
  
        if (is_null($notification)) {
            return $this->sendError('Notification not found.');
        }
   
        return $this->sendResponse(new NotificationResource($notification), 'Notification retrieved successfully.');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mark_as_read(Request $request, Notification $notification): JsonResponse
    {
        $input = $request->all();
   
        $notification->read = true;
        $notification->save();
   
        return $this->sendResponse(new NotificationResource($notification), 'Notification marked as read.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification): JsonResponse
    {
        $notification->delete();
   
        return $this->sendResponse([], 'Notification deleted successfully.');
    }
}
