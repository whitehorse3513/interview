@extends('layouts.frontend')
@section('section')

<section class="header">
    <div class="back">
        <button type="button" class="btn"><i class='fas fa-arrow-left'></i></button>
    </div>
    <div class="message">
        <p class="greeting">Welcome</p>
        <p class="notice">please fill out the information below and click start interview</p>
    </div>
    <div class="logo">
        <p >Logo</p>
    </div>
</section>
<main>
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="form-horizontal" action="{{route('saveApplicant')}}" method="POST">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control form-control-lg" id="name" placeholder="Full name" name="name" value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <input type="number" class="form-control form-control-lg" id="age" placeholder="Age" name="age" value="{{ old('age') }}">
            </div>
            <div class="form-group">
                <input type="email" class="form-control form-control-lg" id="email" placeholder="Email" name="email" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <input type="tel" class="form-control form-control-lg" id="phone" placeholder="Phone" name="phone" value="{{ old('phone') }}">
            </div>
            <div class="camera_container">
                <div id="my_camera"></div>
                <p class="snap" onclick="take_snapshot()" data-val="">Take<br>Picture</p>
            </div>
            <input type="hidden" name="image" class="image-tag">

            <div class="form-group text-center">
                <button type="submit" class="btn interview" href="interview.html">Start interview</button>
            </div>
        </form>
    </div>
</main>
@endsection

@section('script')

<!-- Configure a few settings and attach camera -->
<script language="JavaScript">
    Webcam.set({
        width: 150,
		height: 150,
		dest_width: 640,
		dest_height: 480,
		image_format: 'jpeg',
		jpeg_quality: 90,
    });

    Webcam.attach( '#my_camera' );
    var shutter = new Audio();
    shutter.autoplay = true;
    function take_snapshot() {
        var take = $(".snap").data('val');
        if (take === "take")
        {
            shutter.src = './sounds/shutter.mp3';
            Webcam.snap( function(data_uri) {
                $(".image-tag").val(data_uri);
            });
            Webcam.freeze();

            $(".snap").data('val', "retake");
            $(".snap").html('Back To <br> Camera');

        }

        else if (take === "retake") {
            Webcam.unfreeze();
            $(".image-tag").val('')
            $(".snap").html('Take <br> Picture');
            $(".snap").data('val', "take");
        }
    }

    Webcam.on( 'error', function(err) {
        shutter.src = './sounds/error.mp3';
        shutter.play();
        $(".snap").data('val', "error");
    });
    Webcam.on( 'load', function(err) {
        $(".snap").data('val', "take");
    });
</script>
@endsection
