@extends('layouts.backend')
@section('style')
@endsection
@section('section')
<main>
<div class="backend container" style="color:black;">
  <h2 class="setting-title">Application Settings</h2>
  <br>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item active">
      <a class="nav-link active" data-toggle="tab" href="#campaign">Campaigns</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#question">Questions</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#setting">Setting</a>
    </li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div id="campaign" class="tab-pane active"><br>
        <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em; width:100px;" id="createNewCampaign">Add Campaign</a>
        <div class="flow-auto">
            <table class="table table-hover campaign">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="campaign_modal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="CampaignCrudModal"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <form id="campaigndata">
                    <div class="modal-body">
                        <input type="hidden" id="campaign_id" name="campaign_id" value="">
                        <input type="text" class="form-control" id="name" name="name" value="">
                    </div>
                    <div class="modal-footer">
                        <input type="submit" value="Submit" id="campaign_submit" class="btn btn-sm btn-outline-danger py-0">
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="question" class="tab-pane fade"><br>
        <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em; width:100px;" id="createNewQuestion">Add Question</a>
        <div class="flow-auto">
            <table class="table table-hover question">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Campaign</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="question_modal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="QuestionCrudModal"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                    <div class="modal-body">
                        <form id="questiondata">
                            <input type="hidden" id="question_id" name="question_id" value="">
                            <input type="text" id="name" name="name" value="" class="form-control">
                            <select name="campaign_id" id="campaign_select" class="form-control">
                            </select>
                        </form>
                    </div>

                    <div class="modal-footer">
                    <input type="submit" value="Submit" id="question_submit" class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="setting" class="tab-pane fade"><br>
        <form id="settingdata">
            <select name="campaign_id" class="form-control" id="setting_select">
            </select>
            <input type="submit"  class="form-control" value="Save" id="setting_submit" class="btn btn-sm btn-outline-danger py-0" >
        </form>
    <div>
  </div>
</div>
</main>
@endsection

@section('script')
<script src="{{ asset('assets/js/sweetalert2@9.js') }}"></script>
<script>

var campaign_root_url = <?php echo json_encode(route('campaigns')) ?>;
var campaign_store = <?php echo json_encode(route('createCampaign')) ?>;
var question_root_url = <?php echo json_encode(route('questions')) ?>;
var question_store = <?php echo json_encode(route('createQuestion')) ?>;
var setting_root_url = <?php echo json_encode(route('setting')) ?>;
var setting_store = <?php echo json_encode(route('saveSetting')) ?>;

$(document).ready(function () {
    get_campaign_data();
    get_question_data();
    get_setting_data();

    $.ajaxSetup({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });

    //Get all Campaign
    function get_campaign_data() {

        $.ajax({
            url: campaign_root_url,
            type:'GET',
            data: { }
        }).done(function(data){
            campaign_table_data_row(data)
            append_select_option_row(data)
        });
    }

    //Get all Question
    function get_question_data() {
        $.ajax({
            url: question_root_url,
            type:'GET',
            data: { }
        }).done(function(data){
            question_table_data_row(data)
        });
    }

    //Get all Setting
    function get_setting_data() {
        $.ajax({
            url: setting_root_url,
            type:'GET',
            data: { }
        }).done(function(data){
            $('#setting_select').val(data.campaign_id);
        });
    }

    //Save data into database
    $('body').on('click', '#setting_submit', function (event) {
        event.preventDefault()
        var campaign_id = $("#setting_select").val();

        $.ajax({
            url: setting_store,
            type: "POST",
            data: {
                campaign_id: campaign_id,
            },
            dataType: 'json',
            success: function (data) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Success',
                    showConfirmButton: false,
                    timer: 1500
                })
            },
            error: function (data) {
                console.log('Error......');
            }
        });
    });

    //Campaign table row
    function campaign_table_data_row(data) {

        var	rows = '';

        $.each( data, function( key, value ) {

            rows = rows + '<tr>';
            rows = rows + '<td>'+(key+1)+'</td>';
            rows = rows + '<td>'+value.name+'</td>';
            rows = rows + '<td data-id="'+value.id+'">';
                    rows = rows + '<a class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;" id="editCampaign" data-id="'+value.id+'" data-toggle="modal" data-target="#campaign_modal"><i class="fas fa-edit"></i></a> ';
                    rows = rows + '<a class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;" id="deleteCampaign" data-id="'+value.id+'" ><i class="fas fa-times"></i></a> ';
                    rows = rows + '</td>';
            rows = rows + '</tr>';
        });

        $(".campaign tbody").html(rows);
    }

    //Campaign table row
    function append_select_option_row(data) {

        var	rows = '';

        $.each( data, function( key, value ) {
            rows = rows + '<option value='+ value.id +'>'+value.name+'</option>';
        });

        $("#campaign_select").html(rows);
        $("#setting_select").html(rows);
    }

    //Question table row
    function question_table_data_row(data) {

        var	rows = '';

        $.each( data, function( key, value ) {

            rows = rows + '<tr>';
            rows = rows + '<td>'+(key+1)+'</td>';
            rows = rows + '<td>'+value.campaign_name+'</td>';
            rows = rows + '<td>'+value.name+'</td>';
            rows = rows + '<td data-id="'+value.id+'">';
                    rows = rows + '<a class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;" id="editQuestion" data-id="'+value.id+'" data-toggle="modal" data-target="#question_modal"><i class="fas fa-edit"></i></a> ';
                    rows = rows + '<a class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;" id="deleteQuestion" data-id="'+value.id+'" ><i class="fas fa-times"></i></a> ';
                    rows = rows + '</td>';
            rows = rows + '</tr>';
        });

        $(".question tbody").html(rows);
    }

    //Insert Campaign data
    $("body").on("click","#createNewCampaign",function(e){
        e.preventDefault;
        $('#CampaignCrudModal').html("Create Campaign");
        $('#campaign_submit').val("Create Campaign");
        $('#campaign_modal').modal('show');
        $('#campaign_id').val('');
        $('#campaigndata').trigger("reset");

    });

    //Insert Question data
    $("body").on("click","#createNewQuestion",function(e){
        e.preventDefault;
        $('#QuestionCrudModal').html("Create Question");
        $('#question_submit').val("Create Question");
        $('#question_modal').modal('show');
        $('#question_id').val('');
        $('#questiondata').trigger("reset");

    });

    //Save data into database
    $('body').on('click', '#campaign_submit', function (event) {
        event.preventDefault()
        var id = $("#campaigndata #campaign_id").val();
        var name = $("#campaigndata #name").val();

        $.ajax({
            url: campaign_store,
            type: "POST",
            data: {
                id: id,
                name: name,
            },
            dataType: 'json',
            success: function (data) {

                $('#campaigndata').trigger("reset");
                $('#campaign_modal').modal('hide');
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Success',
                    showConfirmButton: false,
                    timer: 1500
                })
                get_campaign_data()
            },
            error: function (data) {
                console.log('Error......');
            }
        });
    });

    //Save data into database
    $('body').on('click', '#question_submit', function (event) {
        event.preventDefault()
        var id = $("#question_id").val();
        var name = $("#questiondata #name").val();
        var campaign_id = $("#questiondata #campaign_select").val();

        $.ajax({
            url: question_store,
            type: "POST",
            data: {
                id: id,
                name: name,
                campaign_id: campaign_id,
            },
            dataType: 'json',
            success: function (data) {

                $('#questiondata').trigger("reset");
                $('#question_modal').modal('hide');
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Success',
                    showConfirmButton: false,
                    timer: 1500
                })
                get_question_data()
            },
            error: function (data) {
                console.log('Error......');
            }
        });
    });

    //Edit modal window
    $('body').on('click', '#editCampaign', function (event) {

        event.preventDefault();
        var id = $(this).data('id');

        $.get(campaign_store+'/'+ id+'/edit', function (data) {
            $('#CampaignCrudModal').html("Edit Campaign");
            $('#campaign_submit').val("Edit Campaign");
            $('#campaign_modal').modal('show');
            $('#campaigndata #campaign_id').val(data.data.id);
            $('#campaigndata #name').val(data.data.name);
        })
    });

    //Edit modal window
    $('body').on('click', '#editQuestion', function (event) {
        event.preventDefault();
        var id = $(this).data('id');

        $.get(question_store+'/'+ id+'/edit', function (data) {
            $('#QuestionCrudModal').html("Edit Question");
            $('#question_submit').val("Edit Question");
            $('#question_modal').modal('show');
            $('#question_id').val(data.data.id);
            $('#questiondata #campaign_select').val(data.data.campaign_id);
            $('#questiondata #name').val(data.data.name);
        })
    });

    //DeleteCampaign
    $('body').on('click', '#deleteCampaign', function (event) {
        if(!confirm("Do you really want to do this?")) {
        return false;
        }

        event.preventDefault();
        var id = $(this).attr('data-id');

        $.ajax(
            {
            url: campaign_store+'/'+id,
            type: 'DELETE',
            data: {
                    id: id
            },
            success: function (response){

                Swal.fire(
                'Remind!',
                'Campaign deleted successfully!',
                'success'
                )
                get_campaign_data()
                get_question_data()
            }
        });
        return false;
    });
    //DeleteQuestion
    $('body').on('click', '#deleteQuestion', function (event) {
        if(!confirm("Do you really want to do this?")) {
        return false;
        }

        event.preventDefault();
        var id = $(this).attr('data-id');

        $.ajax(
            {
            url: question_store+'/'+id,
            type: 'DELETE',
            data: {
                    id: id
            },
            success: function (response){

                Swal.fire(
                'Remind!',
                'Question deleted successfully!',
                'success'
                )
                get_question_data()
            }
        });
        return false;
    });
});
</script>
@endsection
