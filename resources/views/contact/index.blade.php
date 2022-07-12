@extends("layouts.app")
@section("content")

<div class="container">
    <div class="row">

        <div class="col">
            <div class="">
                @if(session('null'))
                    <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                        <div>
                            <i class="fas fa-exclamation-triangle fa-1x fa-fw me-2"></i>
                            {{session('null')}}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(count($contacts) != 0)
                    <div class="">
                        <input type="checkbox" id="checkAll" class="form-check-input">
                        <label class="form-check-label" for="checkAll">Select All</label>
                    </div>
                @endif

                <form action="{{route('contact.bulkAction')}}" id="bulk_action" method="post">
                    @csrf
                </form>

                <ul class="list-group">
                    @forelse($contacts as $contact)

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           form="bulk_action"
                                           name="contact_ids[]"
                                           value="{{$contact->id}}"
                                           id="contact{{ $contact->id  }}"
                                    />
                                    <label class="form-check-label" for="contact{{ $contact->id  }}">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div id="pf-small-img" class="border border-1 rounded-circle me-2" >
                                                @if($contact->photo)
                                                    <img src="{{ asset('storage/photo/'.$contact->photo) }}"  alt='{{$contact->photo}}' class="" alt="">
                                                @elseif($contact->photo==null)
                                                    <img src="{{asset('user-default.png')}}" class="" alt="">
                                                @endif
                                            </div>
                                            <div class="">
                                                <p class="fw-bold mb-0">
                                                    {{ $contact->name }}
                                                </p>
                                                <p class="text-black-50 mb-0">
                                                    {{ $contact->phone }}
                                                </p>
                                                <p>
                                                    {{$contact->created_at}}
                                                </p>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                            </div>
                            <div class="btn-group">
{{--                                //single share form--}}
                                <form
                                    action="{{route('contact.bulkActionOnce',$contact->id)}}"
                                    id="bulk-action-once"
                                    method="post">
                                    @csrf
                                    <input type="hidden"
                                           name="contact_ids[]"
                                           value="{{$contact->id}}"
                                    >

                                </form>
                                <button class="shareBtn btn btn-sm btn-outline-primary" id="contact_id{{$contact->id}}" data-bs-toggle="modal" data-bs-target="#emailModal{{$contact->id}}">
                                    <i class="fa-solid fa-fw  fa-paper-plane"></i>
                                </button>


                                    <a href="{{ route('contact.edit',$contact->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-fw fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{route('contact.destroy',$contact->id)}}" method="post" class="d-inline-block">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-fw fa-trash-alt"></i>
                                        </button>
                                    </form>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-black-50 h2">
                            There is no data
                        </li>
                    @endforelse
                </ul>

                <div class="d-flex justify-content-between align-items-center my-2">
                    <div class="">
                        <div class="d-flex">
                            <select class="form-select me-2" form="bulk_action" name="functionality">
                                <option value="">Select Action</option>
                                <option value="1">Share Contact</option>
                                <option value="2">Delete Contact</option>
                            </select>
                            <div class="">
                                <button class="btn btn-outline-primary" form="bulk_action" >Submit</button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        {{ $contacts->appends(Request::all())->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<!-- Modal -->

@foreach($contacts as $contact)
<div class="modal fade" id="emailModal{{$contact->id}}" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">Receiver Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="">
{{--                    <input type="text" name="contact" form="bulk-action-once" class="form-control">--}}
                    <p>{{$contact->id}}</p>
                </div>
                <div class="">
                    <label class="form-label" for="">Recipient Email</label>
                    <input type="text" name="email[]" form="bulk-action-once" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cancelAction()"  class="btn btn-secondary">Close</button>
                <button type="submit" form="bulk-action-once" class="btn btn-primary">
                    <i class="fa-solid fa-paper-plane"></i> Share
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

{{--for multiple selected--}}
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">Modal title</h5>
                <button type="button" onclick="cancelAction()" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="">
                    <label class="form-label" for="">Recipient Email</label>
                    <input type="text" name="email" form="bulk_action" class="form-control">
                </div>
                <div class="">
                    <label class="form-label" for="">Message</label>
                    <textarea name="message" form="bulk_action" class="form-control" id="" cols="30" rows="7"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cancelAction()"  class="btn btn-secondary">Close</button>
                <button type="submit" form="bulk_action" class="btn btn-primary">
                    <i class="fa-solid fa-paper-plane"></i> Share
                </button>
            </div>
        </div>
    </div>
</div>

{{--    end Modal Box--}}




@endsection
@push('js')
    <script>

        let emailModal = document.querySelector('#emailModal');
        let myEmailModal =new bootstrap.Modal(emailModal);
        let contactBulkFunctionalitySelect = document.querySelector(`[name="functionality"]`);
        let shareBtn = document.querySelector('.shareBtn');
        console.log(shareBtn)

        contactBulkFunctionalitySelect.addEventListener('change',function (){
            let selected = Number(this.value);
            console.log(selected);

            if(selected === 1){
                myEmailModal.show();
            }
        })

        function cancelAction(){
            contactBulkFunctionalitySelect.value = "";
            myEmailModal.hide();
        }
    </script>

    <script>
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>

    <script>

    </script>
@endpush
