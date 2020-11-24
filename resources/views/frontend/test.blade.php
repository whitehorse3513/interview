@extends('layouts.frontend')
@section('section')
<section class="header">
    <div class="message">
        <p class="greeting_client">Hi, {{ session('applicant')->name }}!</p>
        <p class="question">Where do you see yourself in 5 years from now?</p>
    </div>
</section>
<section class="notification_container">
    <div class="notification">
        <div class="notification_body">
            <p class="notice">If this is your first time using this app, would you like to conduct a test interview first?</p>
        </div>
        <div class="notification_footer">
            <a class="btn btn-danger" href="{{ route('test.no') }}">No</a>
            <a class="btn btn-success" href="{{ route('test.yes') }}">Yes</a>
        </div>
    </div>
</section>
<main>
    <div class="container">
        <form class="form-horizontal" action="/action_page.php">
            <div id="rec_camera"></div>
            <input type="hidden" name="image" class="image-tag">
        </form>
    </div>
    <div class="back_image">
        <div class="image_container">
            <img src="{{ asset('assets/images/1.png') }}">
        </div>
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

    Webcam.attach( '#rec_camera' );
    var shutter = new Audio();
    shutter.autoplay = true;
    function take_snapshot() {
        var take = $(".snap").data('val');
        if (take === "take")
        {
            shutter.src = 'shutter.mp3';
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
        shutter.src = 'error.mp3';
        shutter.play();
        $(".snap").data('val', "error");
    });
    Webcam.on( 'load', function(err) {
        $(".snap").data('val', "take");
    });
</script>
@endsection
