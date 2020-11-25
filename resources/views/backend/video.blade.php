@extends('layouts.backend')
@section('style')
<style>
    .col-md-9, .col-md-3 {
        padding: 0;
    }

    .card {
        margin: 0;
    }
    video {
        width: 100%;
        height: 60rem;
    }

    .form-group {
        width: fit-content;
        margin: 30px auto;
    }
    .row {
        margin: 0;
    }

    .video-list {
        width: 100%;
        height: 10rem;
        background-color: rebeccapurple;
        padding: 1rem;
    }

    .video-list ul {
        display: flex;
        padding: 0px;
        overflow-x: auto;
    }

    .video-list ul li {
        list-style-type: none;
        margin-left: 10px;
    }

    .video-list ul li a img{
        height: 8rem;
    }

    .video-list ul li.selected {
        border: 3px solid yellowgreen;
    }

    .video-player {
        position: relative;
    }

    .question-title {
        position: absolute;
        top: 0;
        left: 0;
        color: red;
        padding: 0 20px;
        width: 100%;
        z-index: 1;
    }

    .rate_container {
        width: fit-content;
        margin: auto;
    }

    @media screen and (max-width: 767px) {
        video {
            height: 40rem;
        }
    }
    @media screen and (max-width: 500px) {
        video {
            height: 30rem;
        }
    }
</style>
<!-- default styles -->
<link rel="stylesheet" href="{{ asset('assets/css/star-rating.min.css') }}">

@endsection
@section('section')
<main>
    <div class="row">
        <div class="col-md-9 col-sm-8 col-xs-12">
            <div class="video-view">
                <div class="video-player">
                    <video controls>
                        <source src="#" type="video/mp4">
                            Your browser does not support HTML video.
                    </video>
                    <div class="question-title">
                        <h3>
                            <marquee width="100%" direction="right" height="100px" id="question"></marquee>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="video-list" id="video-list">
                <ul>
                    @foreach($interviews as $key=>$interview)
                    <li><a href="{{ asset('storage/Interview/'.$interview->file) }}" data-id="{{ $interview->id }}"  data-rate="{{ $interview->rate }}"  data-comment="{{ $interview->comment }}"  data-question="{{ $interview->question->name }}"><img src="{{ asset('storage/Avatar/'.$applicant->image) }}"/></a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <span style="color: black;">#{{ $applicant->id }}</span>
                    <p>{{ $applicant->name }}</p>
                </div>
                <div class="card-body">
                    <div class="card-image">
                        <img src="{{ asset('storage/Avatar/'.$applicant->image) }}" width="150" height="150"/>
                    </div>
                </div>
                <div class="card-footer">
                    <p>
                        <span class="fas fa-star"></span>
                        <span class="fas fa-star"></span>
                        <span class="fas fa-star"></span>
                        <span class="fas fa-star"></span>
                        <span class="fas fa-star"></span>
                    </p>
                    <p style="position: absolute; width:fit-content;">
                        <span class="fas {{ $applicant->mark >= 1 ? 'fa-star checked' : ($applicant->mark >= 0.5 ? 'fa-star-half checked' : 'fa-star')}}"></span>
                        <span class="fas {{ $applicant->mark >= 2 ? 'fa-star checked' : ($applicant->mark >= 1.5 ? 'fa-star-half checked' : 'fa-star')}}"></span>
                        <span class="fas {{ $applicant->mark >= 3 ? 'fa-star checked' : ($applicant->mark >= 2.5 ? 'fa-star-half checked' : 'fa-star')}}"></span>
                        <span class="fas {{ $applicant->mark >= 4 ? 'fa-star checked' : ($applicant->mark >= 3.5 ? 'fa-star-half checked' : 'fa-star')}}"></span>
                        <span class="fas {{ $applicant->mark >= 5 ? 'fa-star checked' : ($applicant->mark >= 4.5 ? 'fa-star-half checked' : 'fa-star')}}"></span>
                    </p>
                    <p><i class='fas fa-child'></i> {{ $applicant->age }}</p>
                </div>
            </div>
            <div class="comment">
                <label for="comment">Comment <span class="rating_body" style="font-size: small;"></span></label>
                <textarea name="comment" id="comment" rows="5"></textarea>

                <div class="rate_container"><input id="input-id" type="text" class="rating" data-min="0" data-max="5" data-size="sm" data-step="0.5" name="rate" ></div>
                <input type="hidden" name="interview_id" id="interview_id" value="">

                <div class="form-group">
                    <button type="button" class="btn btn-success" id="saveComment">Save</button>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
<script src="{{ asset('assets/js/sweetalert2@9.js') }}"></script>
<!-- important mandatory libraries -->
<script src="{{ asset('assets/js/star-rating.min.js') }}"></script>

<script>
    $('#input-id').rating({ 'update': 0, 'showCaption': false, 'showClear': false });
    var video_player = document.getElementById("video-list");
    var links = video_player.getElementsByTagName('a');

    for (var i=0; i<links.length;i++) {
        links[i].onclick = handler;
    }

    function handler(e) {
        e.preventDefault();
        var videotarget = this.getAttribute("href");
        var video = document.querySelector(".video-player video");
        var source = document.querySelectorAll(".video-player video source");
        source[0].src = videotarget;
        video.load();

        $("ul li").removeClass('selected');
        this.parentNode.classList.add('selected');
        var question = this.getAttribute('data-question');
        var interview_id = this.getAttribute('data-id');
        var rate = this.getAttribute('data-rate');
        var comment = this.getAttribute('data-comment');
        $('#question').text(question);
        $('#interview_id').val(interview_id);
        $("#comment").val(comment);
        $('.rating_body').text('Rating (' + parseFloat(rate) + ')');
    }

    const slider = document.querySelector('ul');
    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        slider.classList.add('active');
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });
    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.classList.remove('active');
    });
    slider.addEventListener('mouseup', () => {
        isDown = false;
        slider.classList.remove('active');
    });
    slider.addEventListener('mousemove', (e) => {
        if(!isDown) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 1; //scroll-fast
        slider.scrollLeft = scrollLeft - walk;
        console.log(walk);
    });

    $.ajaxSetup({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });


    $('body').on('click', '#saveComment', function (event) {
        event.preventDefault();
        var id = $('#interview_id').val();
        if (!id) {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'please select interview first!',
                showConfirmButton: false,
                timer: 1500
            })
            return false;
        }
        var comment = $("#comment").val();
        if(!comment)
        {
            comment = '\0';
        }
        var rate = $("#input-id").val();

        $.ajax(
            {
            url: '/saveComment/'+id,
            type: 'POST',
            data: {
                id: id,
                comment: comment,
                rate: rate
            },
            success: function (response){
                if (response.success) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
                else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
                $('#interview_id').val('');
                $("#comment").val('');
                $("#input-id").rating('reset');
                $('.rating_body').text('Rating (' + parseFloat(rate) + ')');
            }
        });
        return false;
    });

</script>
@endsection
