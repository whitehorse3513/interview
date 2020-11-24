@extends('layouts.backend')
@section('style')
<style>
.card-header a i:hover {
    color: darkred;
}
.to-top {
    position: fixed;
    bottom : 3rem;
    right: 3rem;
    display: none;
}
#scrollToTopBtn {
    border-radius: 50%;
    padding : 10px;
    width : 40px;
    height : 40px;
    text-align : center;
    background-color: lightgray;
    border: none;
    opacity: 0.7;
}
#scrollToTopBtn:hover {
    background-color: rgb(129, 99, 223);
    opacity: 1;
}
.showBtn {
    display: block;
}
</style>
@endsection
@section('section')
<main>
    <div class="setting-container">
        <div class="content">
            <div class="row">
                <div class="col-md-5 col-sm-6">
                    <h3>
                        <span class="total">{{$applicants->total()}}</span> Total Applicants /
                        <span style="color: lightblue;"><span class="total">{{$applicants->count()}}</span> Applicants</span>
                    </h3>
                </div>
                <div class="col-md-5 col-sm-6">
                    <label for="sort"> Sort : </label>
                    <select id="sort" name="sort">
                        <option value="ranking">Ranking</option>
                        <option value="age">Age</option>
                        <option value="date">Date</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card-container">
        <div class="row">
            @foreach($applicants as $key => $applicant)
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <span>#{{ $applicant->id }}</span>
                        <a href="{{ route('interview.video', $applicant->slug ) }}" style="width: 100%;"><p>{{ $applicant->name }}</p></a>
                        <form action="{{route('deleteApplicant',$applicant->id)}}" method="POST" id="del-applicant-{{$applicant->id}}" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <a style="float: right;" id="deleteButton" data-id="{{ $applicant->id }}"><i class='fas fa-times'></i></a>
                    </div>
                    <div class="card-body">
                        <div class="card-image">
                            <img src="{{ asset('storage/Avatar/'.$applicant->image) }}" width="150" height="150"/>
                        </div>
                    </div>
                    <div class="card-footer">
                        <p style="position: absolute; width:fit-content;">
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
                        <p  style="text-align: right;"><i class='fas fa-child'></i> {{ $applicant->age }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row" style="text-align:center">
            {{$applicants->links()}}
        </div>

        <div class="to-top">
            <a class="btn btn-success" id="scrollToTopBtn"><i class="fas fa-arrow-up"></i></a>
        </div>
    </div>
</main>
@endsection

@section('script')
<script src="{{ asset('assets/js/sweetalert2@9.js') }}"></script>
<script>
$(document).ready(function () {
    var sort = '<?php echo $sort;?>';
    $("#sort").val(sort);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#sort').change(function() {
        window.location = 'home?sort='+$('#sort').val();
    });

    //DeleteCampaign
    $('body').on('click', '#deleteButton', function (event) {
        if(!confirm("Do you really want to do this?")) {
            return false;
        }

        var id = $(this).attr('data-id');

        document.getElementById('del-applicant-'+id).submit()
    });

    var scrollToTopBtn = document.getElementById("scrollToTopBtn");
    var rootElement = document.documentElement;
    function scrollToTop () {
        rootElement.scrollTo({
            top: 0,
            behavior: "smooth"
        })
    }

    scrollToTopBtn.addEventListener("click", scrollToTop);

    function handleScroll() {
       // Do something on scroll
        var scrollTotal = rootElement.scrollHeight - rootElement.clientHeight
        if ((rootElement.scrollTop / scrollTotal ) > 0.50 ) {
            // Show button
            scrollToTopBtn.parentNode.classList.add("showBtn");
        } else {
            // Hide button
            scrollToTopBtn.parentNode.classList.remove("showBtn");
        }
    }
    document.addEventListener("scroll", handleScroll);
});
</script>

@endsection
