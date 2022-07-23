@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-header">
                        {{__('Update Contact')}}
                    </div>
                    <div class="card-body">
                        <form action="{{route('contact.update',$contact->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div id="pf-img" class="@error('photo') border border-danger is-invalid mb-0 @enderror mb-3  w-100 rounded d-flex flex-column justify-content-center align-items-center border border-1 position-relative">
                                <div class="position-absolute bottom-0 end-0">
                                        <span id="photoInputBtn" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-camera-alt fa-fw fa-2x"></i>
                                        </span>
                                </div>
                                <input type="file" class="d-none" id="photoInput" name="photo" accept="image/jpeg,image/png">
                                @if($contact->photo)
                                    <img src="{{asset('storage/photo/'.$contact->photo)}}" id="photoPreview" class=" " alt="">
                                @elseif($contact->photo == null)
                                    <img src="{{asset('user-default.png')}}" id="photoPreview" class=" " alt="">
                                @endif

                            </div>
                            @error('photo')
                            <div class="invalid-feedback mb-3">
                                <span>{{$message}}</span>
                            </div>
                            @enderror
                            <div class="form-floating mb-3">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="floatingName" value="{{old('name',$contact->name)}}">
                                <label for="floatingName">Name</label>
                                @error('name')
                                <div class="invalid-feedback">
                                    <span>{{$message}}</span>
                                </div>
                                @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror" id="floatingPhone" placeholder="0987654321" value="{{old('phone',$contact->phone)}}">
                                <label for="floatingPhone">Phone Number</label>
                                @error('phone')
                                <div class="invalid-feedback">
                                    <span>{{$message}}</span>
                                </div>
                                @enderror
                            </div>
                            <div>
                                <button class="btn btn-lg btn-primary form-control ">{{__('Update Contact')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        let photoPreview = document.getElementById('photoPreview');
        let photoInputBtn = document.getElementById('photoInputBtn');
        let photoInput = document.getElementById('photoInput');

        photoPreview.addEventListener('click',_=>photoInput.click());
        photoInputBtn.addEventListener('click',_=>photoInput.click());

        photoInput.addEventListener("change",_=>{
            let file = photoInput.files[0];
            let reader = new FileReader();
            reader.onload = function (){
                photoPreview.src = reader.result;
            }
            reader.readAsDataURL(file);
        })
    </script>
@endpush
