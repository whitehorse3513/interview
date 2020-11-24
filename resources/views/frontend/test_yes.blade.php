@extends('layouts.frontend')
@section('section')
<section class="header">
    <div class="message">
        <p class="greeting_client">Hi, {{ session('applicant')->name }}!</p>
        <p class="question">Where do you see yourself in 5 years from now?</p>
    </div>
</section>
<main>
    <div class="countdown">
        <p>10</p>
    </div>
    <div class="hint">
        <p>You have 10 seconds to prepare for each question.</p>
        <p>Once the countdown is finished the recording will start.</p>
        <p class="p-2">When your finished, click "next question"</p>
        <a class="btn btn-success" href="{{ route('test.no') }}">OK</a>
    </div>
    <div class="container">


        <form class="form-horizontal" action="/action_page.php">

            <input type="hidden" name="image" class="image-tag">
        </form>
    </div>
</main>
<div id="rec_camera"></div>

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

    Webcam.attach( '#rec_camera' );
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
