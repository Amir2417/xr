<?php

namespace App\Http\Controllers\Agent;

use Exception;
use App\Models\SupportChat;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Http\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Constants\SupportTicketConst;
use App\Models\SupportTicketAttachment;
use Illuminate\Support\Facades\Validator;
use App\Events\Admin\SupportConversationEvent;

class SupportTicketController extends Controller
{
    /**
     * Method for view agent support ticket page
     * @return view
     */
    public function index(){
        $page_title         = "Support Tickets";
        $support_tickets    = SupportTicket::authTicketsAgent()->orderByDesc("id")->paginate(10);

        return view('agent.sections.support-ticket.index',compact(
            'page_title',
            'support_tickets'
        ));
    }
    /**
     * Method for view create support ticket page
     * @return view
     */
    public function create(){
        $page_title         = "Add New Ticket";

        return view('agent.sections.support-ticket.create',compact(
            'page_title',
        ));
    }
    /**
     * Method for store support ticket information
     * @param Illuminate\Http\Request $request
     */
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'              => "required|string|max:60",
            'email'             => "required|string|email|max:150",
            'subject'           => "required|string|max:255",
            'desc'              => "nullable|string|max:5000",
            'attachment'        => "required|array",
            'attachment.*'      => "required|file|max:204800",
        ]);
        $validated = $validator->validate();
        $validated['token']         = generate_unique_string('support_tickets','token',16);
        $validated['agent_id']      = auth()->user()->id;
        $validated['status']        = SupportTicketConst::PENDING;
        $validated['type']          = SupportTicketConst::TYPE_AGENT;
        $validated['created_at']    = now();
        $validated = Arr::except($validated,['attachment']);

        try{
            $support_ticket_id = SupportTicket::insertGetId($validated);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }

        if($request->hasFile('attachment')) {
            $validated_files = $request->file("attachment");
            $attachment = [];
            $files_link = [];
            foreach($validated_files as $item) {
                $upload_file = upload_file($item,'support-attachment');
                if($upload_file != false) {
                    $attachment[] = [
                        'support_ticket_id'         => $support_ticket_id,
                        'attachment'                => $upload_file['name'],
                        'attachment_info'           => json_encode($upload_file),
                        'created_at'                => now(),
                    ];
                }

                $files_link[] = get_files_path('support-attachment') . "/". $upload_file['name'];
            }

            try{
                SupportTicketAttachment::insert($attachment);
            }catch(Exception $e) {
                $support_ticket_id->delete();
                delete_files($files_link);

                return back()->with(['error' => ['Oops!! Failed to upload attachment. Please try again.']]);
            }
        }
        return redirect()->route('agent.support.ticket.index')->with(['success' => ['Support ticket created successfully!']]);
    }
    /**
     * Display the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function conversation($encrypt_id)
    {
        $page_title        = " | Conversation";
        $support_ticket_id = decrypt($encrypt_id);
        $support_ticket    = SupportTicket::findOrFail($support_ticket_id);
        
        return view('agent.sections.support-ticket.conversation', compact(
            'page_title',
            'support_ticket',
        ));
    }

    public function messageSend(Request $request) {

        $validator = Validator::make($request->all(),[
            'message'       => 'required|string|max:200',
            'support_token' => 'required|string',
        ]);
        if($validator->fails()) {
            return Response::error($validator->errors(),null,400);
        }
        $validated = $validator->validate();

        $support_ticket = SupportTicket::notSolved($validated['support_token'])->first();
        if(!$support_ticket) return Response::error(['error' => ['This support ticket is closed.']]);
        
        $data = [
            'support_ticket_id'         => $support_ticket->id,
            'sender'                    => auth()->user()->id,
            'sender_type'               => userGuard()['type'],
            'message'                   => $validated['message'],
            'receiver_type'             => "ADMIN",
        ];

        try{
            $chat_data = SupportChat::create($data);
        }catch(Exception $e) {
            return $e;
            $error = ['error' => ['SMS Sending failed! Please try again.']];
            return Response::error($error,null,500);
        }

        try{
            event(new SupportConversationEvent($support_ticket,$chat_data));
        }catch(Exception $e) {
            return $e;
            $error = ['error' => ['SMS Sending failed! Please try again.']];
            return Response::error($error,null,500);
        }
    }
}