<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpParser\Node\Stmt\TryCatch;

/**
 * @OA\Info(
 *      title="Note",
 *      version="1.0.0",
 *      description=" Crud Opration",
 * )
 * APIs for managing note crud opration.
 */

class NoteController extends Controller
{

protected function publishMessage($messageData)
    {
        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST'),
            false, 'AMQPLAIN', null, 'en_US',  2.0,   null, false, 0  );

        $channel = $connection->channel();

        $channel->queue_declare(env('RABBITMQ_QUEUE'), false, true, false, false);

        $messageBody = json_encode(['data' => $messageData]);
        $message = new AMQPMessage($messageBody);

        $channel->basic_publish($message, '', env('RABBITMQ_QUEUE'));

        $channel->close();
        $connection->close();
    }

 
  /**
 * @OA\Get(
 *     path="/api/show",
 *     summary="Get a list of notes",
 *    
 *     @OA\Response(response="200", description="List of notes"),
 *     @OA\Response(response="404", description="Notes not found")
 * )
 */
    public function show(Note $note)
    {
      try {
        
        $message=new NoteController();
        $message->publishMessage(['action'=>'show', 'data'=>'title,content']);
        return response()->json($note->all(), 200);

      } catch (\Throwable $th) {
        throw $th;
      }
    }


  /**
 * @OA\Get(
 *     path="/api/search",
 *     summary="Get a list of notes",
 *     @OA\Parameter(
 *         name="title",
 *         in="query",
 *         description="Filter notes by title",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(response="200", description="List of notes"),
 *     @OA\Response(response="404", description="Notes not found")
 * )
 */
    public function search(Request $request)
    {
      try {
        
        $notes=Note::where('title', 'like', '%' . $request->title . '%')->get();
        if(!$notes){
           return response()->json('no result',401);
        }


        $message=new NoteController();
        $message->publishMessage(['action'=>'search', 'data'=>'title,content']);

       return response()->json($notes, 200);

      } catch (\Throwable $th) {
        throw $th;
      }
    }
    
    
/**
 * @OA\Post(
 *     path="/api/store",
 *     summary="Create a new note",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(property="useidr_", type="integer", example="1"),
 *                 @OA\Property(property="title", type="string", example="New Note"),
 *                 @OA\Property(property="content", type="string", example="Note content")
 *             )
 *         )
 *     ),
 *     @OA\Response(response="201", description="Note created successfully"),
 *     @OA\Response(response="422", description="Validation error")
 * )
 */
    public function store(Request $request)
    {
       
      try {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
    
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        
        $note = Note::create($request->all());

        $message=new NoteController();
        $message->publishMessage(['action'=>'create note', 'data'=>'title,content']);
    
        return response()->json(['note' => $note], 200);
      } catch (\Throwable $th) {
        throw $th;
      }
    }
    

 /**
 * @OA\Post(
 *     path="/api/update",
 *     summary="update note note",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(property="useidr_", type="integer", example="1"),
 *                 @OA\Property(property="title", type="string", example="New Note"),
 *                 @OA\Property(property="content", type="string", example="Note content")
 *             )
 *         )
 *     ),
 *     @OA\Response(response="201", description="Note updated successfully"),
 *     @OA\Response(response="422", description="Validation error")
 * )
 */
    public function update(Request $request)
    {
       
       try {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        Note::where('user_id',$request->user_id)->update($request->only('title', 'content'));

        $message=new NoteController();
        $message->publishMessage(['action'=>'update note', 'data'=>'title,content']);

        return response()->json(['note' => Note::find($request->user_id)->all()], 200);

       } catch (\Throwable $th) {
        throw $th;
       }
    }


  
  /**
 * @OA\Get(
 *     path="/api/destroy",
 *     summary="destroy note by id",
 *     @OA\Parameter(
 *         name="id",
 *         in="query",
 *         description="Filter notes by id",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description=" notes deleted"),
 *     @OA\Response(response="404", description="Notes not found")
 * )
 */

    public function destroy($id)
    {
      try {

        $note = Note::find($id);

        if (!$note) {
            return response()->json(['message' => 'note deleted'], 404);
        }

        $note->delete();

        $message=new NoteController();
        $message->publishMessage(['action'=>'delete note', 'data'=>'title,content']);

        return response()->json(['message' => 'Note deleted successfully'], 200);
      } catch (\Throwable $th) {
        throw $th;
      }
    }
}
