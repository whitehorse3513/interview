@extends('layouts.frontend')
@section('style')
<style>
.record-camera {
    position: fixed;
    right: 1rem;
    bottom: 6rem;
    width: 150px;
    height: 150px;
    border-radius: 20px;
    background: #aaa;
    border: 2px solid #8163df;
}

.record-camera video,.record-camera h1 {
    width: 100%;
    height: 100%;
    position: absolute;
}

.record-camera img {
    position: absolute;
    left: 1rem;
    top: 1rem;
    z-index: 1;
}
</style>
@endsection
@section('section')
<section class="header">
    <div class="message">
        <p class="greeting_client">Hi, {{ session('applicant')->name }}!</p>
        <p class="question">{{ $questions[0]->name }}</p>
    </div>
</section>
<section class="notification_container">
    <div class="notification">
        <div class="notification_body">
            <p>You did great!</p>
            <p>Your test interview is done.</p>
            <p>It's now time to move on to the real deal.</p>
        </div>
        <div class="notification_footer">
            <a class="btn btn-success" href="javascript:void(0)" onclick="startRecord()">Real interview</a>
        </div>
    </div>
</section>
<main>
    <div class="countdown">
        <p class="count_number">10</p>
    </div>

    <div class="back_image">
        <div class="container">
            <div class="record-camera">
                <img src="{{ asset('assets/images/loading.gif') }}" width="20" height="20">
                <video id="camera_record" autoplay playsinline></video>
                <input type="hidden" name="applicant_id" value="{{ session('applicant')->id }}">
                <input type="hidden" name="question_id" value="{{ $questions[0]->id }}">
                <input type="hidden" name="image" value="">
                <input type="hidden" name="page" value="{{ $questions->currentPage() }}">
            </div>
        </div>
        <div class="image_container">
            <img src="{{ asset('assets/images/1.png') }}">
        </div>
        <div class="button_container">
            <div class="record">
                <a class="btn btn-success" href="javascript:void()" onclick="stopRecording()">Next</a>
            </div>
        </div>
    </div>
</main>

@endsection

@section('script')
<script src="{{ asset('assets/js/RecordRTC.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2@9.js') }}"></script>
<script type="text/javascript">
    $(".record a").hide();
    $('.record-camera img').hide();

    var recorder;
    var video = document.getElementById('camera_record');
    navigator.mediaDevices.getUserMedia({ video: true, audio: true }).then(function(camera) {
        // preview camera during recording
        video.muted = true;
        video.srcObject = camera;

        // recording configuration/hints/parameters
        var recordingHints = {
            type: 'video',
            mimeType: 'video/webm;codecs=vp8',
        };

        // initiating the recorder
        recorder = RecordRTC(camera, recordingHints);

    }).catch(function(err) {
        Swal.fire(
            'Error!',
            err.message,
            'error'
        );
        $('.notification_footer a').attr('onclick', 'window.location = "/real/start"');
        $('.notification_footer a').text('Refresh page');
    });

    function startRecord() {
        $('.notification').hide();
        var interval = setInterval(function(){
            var count = $(".count_number").text();
            if (count == 1) {
                $(".count_number").text("Ready!");
                $(".record a").show();
                clearInterval(interval);
                startRecording();
            }
            else {
                $(".count_number").text(count-1);
            }
        }, 1000);
    }

    function startRecording() {
        $('.record-camera img').show();
        // starting recording here
        recorder.startRecording();
    }

    function stopRecording(){
        recorder.stopRecording(function() {
            $('.record-camera img').hide();
            // get recorded blob
            var blob = recorder.getBlob();

            // generating a random file name
            var fileName = getFileName('webm');
            var applicant_id = $('input[name=applicant_id]').val();
            var question_id = $('input[name=question_id]').val();
            // we need to upload "File" --- not "Blob"
            var fileObject = new File([blob], fileName, {
                type: 'video/mp4'
            });

            var formData = new FormData();
            var page = parseInt($('input[name=page]').val()) + 1;

            // recorded data
            formData.append('video_blob', fileObject);
            formData.append('_token', '<?php echo csrf_token() ?>');
            formData.append('applicant_id', applicant_id);
            formData.append('question_id', question_id);

            // file name
            formData.append('video_filename', fileObject.name);

            // upload using jQuery
            $.ajax({
                url: '/upload?page='+page, // replace with your own server URL
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(response) {
                    if(response != 'failed') {
                        if(response['data'].length == 0)
                        {
                            window.location = '/thanks';
                        }
                        else {
                            $(".count_number").text("10");
                            $(".record a").hide();
                            startRecord();
                            $('input[name=page]').val(page);
                            $('input[name=question_id]').val(response['data'][0]['id']);
                            $('p.question').text(response['data'][0]['name']);
                        }
                    }
                }
            });
        });
    }

    function getFileName(fileExtension) {
        var d = new Date();
        var year = d.getUTCFullYear();
        var month = d.getUTCMonth();
        var date = d.getUTCDate();
        return 'Interview-' + year + month + date + '-' + getRandomString() + '.' + fileExtension;
    }

    function getRandomString() {
        if (window.crypto && window.crypto.getRandomValues && navigator.userAgent.indexOf('Safari') === -1) {
            var a = window.crypto.getRandomValues(new Uint32Array(3)),
                token = '';
            for (var i = 0, l = a.length; i < l; i++) {
                token += a[i].toString(36);
            }
            return token;
        } else {
            return (Math.random() * new Date().getTime()).toString(36).replace(/\./g, '');
        }
    }
</script>
@endsection
