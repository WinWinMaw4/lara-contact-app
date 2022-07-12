@extends("layouts.app")
@section("content")

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <div class="text-center">
                   @if(Auth::id() == $from->id)
                        <h4>Yor Shared {{ $contacts->count() }} contacts to {{ $to->name}}</h4>
                    @else
                        <h4>Yor Got {{ $contacts->count() }} contacts from {{ $from->name}}</h4>
                    @endif
                    <p>
                        {{ $sharedContact->message }}
                    </p>
                    <ul class="list-group">
                        @forelse($contacts as $contact)
                            <li class="list-group-item d-flex justify-content-start align-items-center">
                                <div id="pf-small-img" class="border border-1 rounded-circle me-2" >
                                    @if($contact->photo)
                                        <img src="{{ asset('storage/photo/'.$contact->photo) }}"  alt='{{$contact->photo}}' class="" alt="">
                                    @elseif($contact->photo==null)
                                        <img src="{{asset('user-default.png')}}" class="" alt="">
                                    @endif
                                </div>
                                <div class="text-start">
                                    <p class="fw-bold mb-0">
                                        {{ $contact->name }}
                                    </p>
                                    <p class="text-black-50 mb-0">
                                        {{ $contact->phone }}
                                    </p>
                                </div>
                            </li>
                        @empty
                        @endforelse
                    </ul>

                    @if(auth()->id() === $sharedContact->from && $sharedContact->status == null)
                        <div class="my-3">
                            <form action="{{route('shared-contact.update',$sharedContact->id)}}" method="post">
                                @csrf
                                @method('put')
{{--                                @foreach(\Illuminate\Support\Facades\Auth::user()->unreadNotifications as $notification)--}}
{{--                                    <input type="text" name="notificationId" value="{{$notification->id}}">--}}
{{--                                @endforeach--}}
                                <input type="hidden" name="action" value="cancel">
                                <button class="btn btn-danger">
                                    {{__('cancel')}}
                                </button>
                            </form>
                        </div>
                    @elseif($sharedContact->status == null)
                        <div class="my-3">
                            <form class="d-inline" action="{{route('shared-contact.update',$sharedContact->id)}}" method="post">
                                @csrf
                                @method('put')

                                @foreach(\Illuminate\Support\Facades\Auth::user()->Notifications as $notification)
                                    <input type="hidden" name="notificationId" value="{{$notification->id}}">
                                @endforeach

                                <input type="hidden" name="action" value="reject">
                                <button class="btn btn-outline-danger">
                                    {{__('reject')}}
                                </button>
                            </form>
                            <form class="d-inline" action="{{route('shared-contact.update',$sharedContact->id)}}" method="post">
                                @csrf
                                @method('put')
                                @foreach(\Illuminate\Support\Facades\Auth::user()->unreadNotifications as $notification)
                                    <input type="hidden" name="notificationId[]" value="{{$notification->id}}">
                                @endforeach
                                <input type="hidden" name="action" value="accept">
                                <button class="btn btn-primary">
                                    {{__('accept')}}
                                </button>
                            </form>
                        </div>
                    @endif



                    @if($sharedContact->status != null && $sharedContact->status != "cancel" && auth()->id() != $sharedContact->from)
                        <div class="alert alert-secondary my-2 text-capitalize">
                            {{$sharedContact->status}}ed
                        </div>
                       @endif
                </div>
            </div>
        </div>
    </div>


@endsection


