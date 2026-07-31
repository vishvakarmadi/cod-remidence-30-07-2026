@extends('admin.admin_layouts')
@section('admin_content')
@php
use App\Models\Admin\Admin;
@endphp
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');



/* Styling the avatar  */
received-chats-img {
    display: inline-block;
    width: 50px;
    float: left;
}

.received-msg {
    display: inline-block;
    padding: 0 0 0 10px;
    vertical-align: top;
    width: 92%;
}
.received-msg-inbox {
    width: 57%;
}

.received-msg-inbox p {
    background: #efefef none repeat scroll 0 0;
    border-radius: 10px;
    color: #646464;
    font-size: 14px;
    margin-left: 1rem;
    padding: 1rem;
    width: 100%;
    box-shadow: rgb(0 0 0 / 25%) 0px 5px 5px 2px;
}
    p {
    overflow-wrap: break-word;
}

/* Styling the msg-sent time  */
.time {
    color: #777;
    display: block;
    font-size: 12px;
    margin: 8px 0 0;
}
.outgoing-chats {
    overflow: hidden;
    margin: 26px 20px;
}

.outgoing-chats-msg p {
    background-color: #3a12ff;
    background-image: linear-gradient(45deg, #ee087f 0%, #DD2A7B 25%, #9858ac 50%, #8134AF 75%, #515BD4 100%);
    color: #fff;
    border-radius: 10px;
    font-size: 14px;
    color: #fff;
    padding: 5px 10px 5px 12px;
    width: 100%;
    padding: 1rem;
    box-shadow: rgb(0 0 0 / 25%) 0px 2px 5px 2px;
}
.outgoing-chats-msg {
        float: right;
        width: 46%;
    }

/* Styling the avatar */
.outgoing-chats-img {
    display: inline-block;
    width: 50px;
    float: right;
    margin-right: 1rem;
}

.received-chats .time {
    text-align:end;
}
.outgoing-chats-msg a{
    color:#fff;
}
    </style>

    <!-- Import this CDN to use icons -->
   


 
    <!-- Main container -->
    <!-- <div class="container"> -->
      
      <!-- msg-header section ends -->
      <div class="block-header">
            <div class="row">
                <div class="col-lg-5 col-md-8 col-sm-12">
                    <h2>View Details</h2>
                </div>
            </div>
        </div>
<div class="card pt-30 ratecard">

<div class="card-body">
      <div class="chat-page">
        <div class="msg-inbox">
            <div class="chats">
            <!-- Message container -->
                <div class="msg-page">
                @foreach($order->reconciliation as $chat)
                    @if($chat->added_by == Auth::guard('admin')->user()->id)
                        <div class="outgoing-chats">
                            <div class="outgoing-msg">
                                <div class="outgoing-chats-msg">
                                    <p>{{$chat->description	}}</p>
                                    @if($chat->file_1 != '' || $chat->file_2 != '' || $chat->file_3 != '' || $chat->file_4 != '')
                                        <p class="row" style="margin-left:0;">
                                            @if($chat->file_1 != '')
                                            <a href="{{ asset('public/uploads/'.$chat->file_1) }}" download="" class="col-md-6">{{$chat->file_1}}</a>
                                            @endif
                                            @if($chat->file_2 != '')
                                            <a href="{{ asset('public/uploads/'.$chat->file_2) }}" download="" class="col-md-6">{{$chat->file_2}}</a>
                                            @endif
                                            @if($chat->file_3 != '')
                                            <a href="{{ asset('public/uploads/'.$chat->file_3) }}" download="" class="col-md-6">{{$chat->file_3}}</a>
                                            @endif
                                            @if($chat->file_4 != '')
                                            <a href="{{ asset('public/uploads/'.$chat->file_4) }}" download="" class="col-md-6">{{$chat->file_4}}</a>
                                            @endif
                                            
                                        </p>
                                    @endif    
                                    <span class="time">{{Admin::getusername($chat->added_by)}}<br>  {{ \Carbon\Carbon::parse($chat->created_at)->format('h:i A') }} | {{ \Carbon\Carbon::parse($chat->created_at)->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @else 
                    <div class="received-chats">
                        <div class="received-msg">
                            <div class="received-msg-inbox">
                                <p>{{$chat->description	}}</p>
                                @if($chat->file_1 != '' || $chat->file_2 != '' || $chat->file_3 != '' || $chat->file_4 != '')
                                        <p class="row" >
                                            @if($chat->file_1 != '')
                                            <a href="{{ asset('public/uploads/'.$chat->file_1) }}" download="" class="col-md-6">{{$chat->file_1}}</a>
                                            @endif
                                            @if($chat->file_2 != '')
                                            <a href="{{ asset('public/uploads/'.$chat->file_2) }}" download="" class="col-md-6">{{$chat->file_2}}</a>
                                            @endif
                                            @if($chat->file_3 != '')
                                            <a href="{{ asset('public/uploads/'.$chat->file_3) }}" download="" class="col-md-6">{{$chat->file_3}}</a>
                                            @endif
                                            @if($chat->file_4 != '')
                                            <a href="{{ asset('public/uploads/'.$chat->file_4) }}" download="" class="col-md-6">{{$chat->file_4}}</a>
                                            @endif
                                            
                                        </p>
                                    @endif 
                                <span class="time">{{Admin::getusername($chat->added_by)}}<br>  {{ \Carbon\Carbon::parse($chat->created_at)->format('h:i A') }} | {{ \Carbon\Carbon::parse($chat->created_at)->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach

                
                </div>
            </div>

          <!-- msg-bottom section -->

          
        </div>
      </div>
    </div>
    </div>

    @endsection

