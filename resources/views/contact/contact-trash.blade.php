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

                    <form action="{{route('contact.bulkForceAction')}}" id="bulk_force_action" method="post">
                        @csrf
                    </form>
                    <ul class="list-group">
                        @forelse($contacts as $contact)

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" form="bulk_force_action" name="contact_ids[]"  value="{{$contact->id}}" id="contact{{ $contact->id  }}">
                                        <label class="form-check-label" for="contact{{ $contact->id  }}">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div id="pf-small-img" class="border border-1 rounded-circle me-2" >
                                                    @if($contact->photo)
                                                        <img src="{{ asset('storage/photo/'.$contact->photo) }}"  alt='{{$contact->photo}}' class="" alt="">
                                                    @elseif($contact->photo==null)
                                                        <img src="{{asset('user-default.png')}}" class="" alt="">
                                                    @endif
                                                </div>
                                                <p class="fw-bold mb-0">
                                                    {{ $contact->name }}
                                                </p>
                                                <p class="text-black-50 mb-0">
                                                    {{ $contact->phone }}
                                                </p>
                                            </div>
                                        </label>
                                    </div>

                                </div>
                                <div class="btn-group">
                                    <a href="{{ route('contact.restore',$contact->id) }}" class="btn btn-sm btn-outline-primary" title="restore">
                                        <i class="fas fa-trash-restore-alt "></i>
                                    </a>
                                    <a href="{{ route('contact.forceDelete',$contact->id) }}" class="btn btn-sm btn-outline-primary" title="forceDelete">
                                        <i class="fas fa-trash-alt "></i>
                                    </a>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center h2 text-black-50">
                               There is no data
                            </li>
                        @endforelse
                    </ul>

                    <div class="d-flex justify-content-between align-items-center my-2">
                        <div class="">
                            <div class="d-flex">
                                <select class="form-select me-2" form="bulk_force_action" name="functionality" required>
                                    <option value="">Select Action</option>
                                    <option value="1">ReStore</option>
                                    <option value="2">Force Delete</option>
                                </select>
                                <div class="">
                                    <button class="btn btn-outline-primary" form="bulk_force_action" >Submit</button>
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

@endsection
@push('js')
    <script>
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
@endpush
